<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use Manychois\Phtml\Internal\ViewLookup;
use Manychois\Phtml\Internal\ViewParser;
use Manychois\Simdom\Document;
use Psr\Container\ContainerInterface;
use RuntimeException;

use function Manychois\Simdom\append;

class ViewEngine
{
    private const string VIEW_LOOKUP_FILENAME = 'phtml_view_lookup_array.php';
    public readonly string $baseDir;
    public readonly string $compiledDir;
    private readonly ViewLookup $viewLookup;
    public readonly bool $isDev;
    private readonly ?ContainerInterface $container;
    /**
     * @var string[]
     */
    private array $viewSourceDirectories = [];

    public function __construct(string $baseDir, string $compiledDir, ?ContainerInterface $container = null, bool $isDev = true)
    {
        $this->baseDir = $baseDir;
        $this->compiledDir = $compiledDir;
        $this->isDev = $isDev;
        $this->container = $container;

        if (!is_dir($compiledDir)) {
            mkdir($compiledDir, 0o777, true);
        }

        $viewLookupFile = $compiledDir . '/' . self::VIEW_LOOKUP_FILENAME;
        if (file_exists($viewLookupFile)) {
            $viewLookup = require $viewLookupFile;
            if (!($viewLookup instanceof ViewLookup)) {
                throw new RuntimeException(sprintf('The file %s is not a valid view lookup file.', $viewLookupFile));
            }
            $this->viewLookup = $viewLookup;
        } else {
            $this->viewLookup = new ViewLookup();
        }
    }

    /**
     * Render a view with the given properties.
     *
     * @param string              $viewName the name of the view to render
     * @param array<string,mixed> $props    The properties to pass to the view
     *
     * @return Document The rendered view as a Document node
     */
    public function render(string $viewName, array $props = []): Document
    {
        $view = $this->getView($viewName);
        $document = Document::create();
        append($document, $view->render($props, []));

        return $document;
    }

    public function getView(string $viewName): AbstractView
    {
        $values = $this->viewLookup->get($viewName);
        $oldCompiled = $values['compiled'] ?? '';
        $oldHash = $values['hash'] ?? '';
        $oldClass = $values['class'] ?? '';
        $oldSource = $values['source'] ?? '';

        if (count($values) > 0 && !$this->isDev) {
            if ('' === $oldCompiled) {
                require_once $oldSource;
            } else {
                require_once $this->compiledDir . '/' . $oldCompiled;
            }

            return $this->instantiateView($oldClass);
        }

        $newSource = $this->findViewSource($viewName);
        if ('' === $newSource) {
            throw new RuntimeException(sprintf('View file for %s not found.', $viewName));
        }

        if (str_ends_with($newSource, '.php')) {
            [$namespace, $newClass] = $this->getPhpClassInfo($newSource);
            if ('' !== $namespace) {
                $newClass = $namespace . '\\' . $newClass;
            }
            require_once $newSource;
            $this->viewLookup->set($viewName, '', $newClass, $newSource, '');
        } else {
            $rawContent = file_get_contents($newSource);
            if (false === $rawContent) {
                throw new RuntimeException(sprintf('Failed to read the file %s', $newSource));
            }
            $newHash = md5($rawContent);
            if ($newSource === $oldSource && $oldHash === $newHash) {
                require_once $this->compiledDir . '/' . $oldCompiled;

                return $this->instantiateView($oldClass);
            }

            $newCompiled = sprintf('%s.php', $viewName);
            $parser = new ViewParser();
            $newClass = $parser->parse($viewName, $rawContent, $this->compiledDir . '/' . $newCompiled);
            $this->viewLookup->set($viewName, $newCompiled, $newClass, $newSource, $newHash);
        }

        $this->viewLookup->export($this->compiledDir . '/' . self::VIEW_LOOKUP_FILENAME);

        return $this->instantiateView($newClass);
    }

    private function findViewSource(string $viewName): string
    {
        if (empty($this->viewSourceDirectories)) {
            $toIterate = [$this->baseDir];
            while (!empty($toIterate)) {
                $dir = array_pop($toIterate);
                assert(is_string($dir));
                $this->viewSourceDirectories[] = $dir;
                $subDirs = glob($dir . '/*', \GLOB_ONLYDIR);
                if (false === $subDirs) {
                    throw new RuntimeException(sprintf('Failed to read the directory %s', $dir));
                }
                foreach ($subDirs as $subDir) {
                    if ($subDir === $this->compiledDir) {
                        continue;
                    }
                    $toIterate[] = $subDir;
                }
            }
        }

        foreach ($this->viewSourceDirectories as $dir) {
            $filePath = $dir . '/' . $viewName . '.php';
            if (file_exists($filePath)) {
                return $filePath;
            }
            $filePath = $dir . '/' . $viewName . '.html';
            if (file_exists($filePath)) {
                return $filePath;
            }
        }

        return '';
    }

    /**
     * Get the namespace and class name from a PHP file.
     *
     * @param string $file the absolute path to the PHP file
     *
     * @return array<string> the namespace and class name
     */
    private function getPhpClassInfo(string $file): array
    {
        $src = file_get_contents($file);
        if (false === $src) {
            throw new RuntimeException(sprintf('Failed to read the file %s', $file));
        }
        $tokens = token_get_all($src);

        $namespace = '';
        $class = '';

        for ($i = 0, $count = count($tokens); $i < $count; ++$i) {
            if (T_NAMESPACE === $tokens[$i][0]) {
                $namespace = '';
                for ($j = $i + 1; $j < $count; ++$j) {
                    if (';' === $tokens[$j]) {
                        break;
                    }
                    if (is_array($tokens[$j])) {
                        $namespace .= $tokens[$j][1];
                    }
                }
                $namespace = trim($namespace);
            }

            if (T_CLASS === $tokens[$i][0]) {
                // Skip "class" tokens that are used for anonymous classes
                if (T_DOUBLE_COLON === $tokens[$i - 1][0]) {
                    continue;
                }

                for ($j = $i + 1; $j < $count; ++$j) {
                    if ('{' === $tokens[$j]) {
                        $class = $tokens[$i + 2][1];
                        break 2;
                    }
                }
            }
        }

        return [$namespace, $class];
    }

    private function instantiateView(string $className): AbstractView
    {
        if (null === $this->container) {
            $instance = new $className($this);
        } else {
            $instance = $this->container->get($className);
        }
        assert($instance instanceof AbstractView);

        return $instance;
    }
}
