<?php

namespace Manychois\Phtml;

use Dom\Comment;
use Dom\DocumentFragment;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use PhpToken;

class Compiler
{
    public readonly Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function compile(string $templateName, mixed $data): string
    {
        $templateFile = $this->findTemplateFile($templateName);
        if (\str_ends_with($templateFile, '.php')) {
            require_once $templateFile;
            $templateClass = $this->getFullClassName($templateFile);
            $template = new $templateClass();
            \assert($template instanceof AbstractTemplate);

            $doc = HTMLDocument::createEmpty();
            $doctype = $doc->implementation->createDocumentType('html', '', '');
            $doctype = $doc->importNode($doctype, true);
            $doc->appendChild($doctype);

            $content = $template->content($doc, $data);
            if ($content !== null) {
                if ($content instanceof Element || $content instanceof Comment) {
                    $doc->appendChild($content);
                } elseif ($content instanceof DocumentFragment) {
                    foreach ($content->childNodes as $child) {
                        if ($child instanceof Element || $child instanceof Comment) {
                            $doc->appendChild($child);
                        }
                    }
                }
            }
        } else {
            $doc = HTMLDocument::createFromFile($templateFile);
        }

        do {
            $rescan = false;
            foreach ($this->config->getTransformers() as $querySelector => $transformer) {
                $elements = $doc->querySelectorAll($querySelector);
                foreach ($elements as $element) {
                    \assert($element instanceof Element);
                    $newNode = $transformer->transform($doc, $element);
                    if ($newNode === null) {
                        $element->remove();
                    } else {
                        $element->parentNode?->replaceChild($newNode, $element);
                        $rescan = true;
                    }
                }
            }
        } while ($rescan);

        return $doc->saveHTML();
    }

    public function findTemplateFile(string $templateName): string
    {
        $toCheck = $this->config->templateDir . '/' . $templateName . '.php';
        if (\file_exists($toCheck)) {
            return $toCheck;
        }

        $uc = \preg_replace_callback('/[^\\/]+$/', function (array $matches) {
            return \str_replace('-', '', \ucwords($matches[0]));
        }, $templateName);
        $toCheck = $this->config->templateDir . '/' . $uc . '.php';
        if (\file_exists($toCheck)) {
            return $toCheck;
        }

        $toCheck = $this->config->templateDir . '/' . $templateName . '.html';
        if (\file_exists($toCheck)) {
            return $toCheck;
        }

        throw new \RuntimeException('Template not found: ' . $templateName);
    }

    private function getFullClassName(string $templateName): string
    {
        $namespace = '';
        $tokens = PhpToken::tokenize(\file_get_contents($templateName));
        for ($i = 0; $i < count($tokens); $i++) {
            if ($tokens[$i]->getTokenName() === 'T_NAMESPACE') {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j]->getTokenName() === 'T_NAME_QUALIFIED') {
                        $namespace = $tokens[$j]->text;
                        break;
                    }
                }
            }

            if ($tokens[$i]->getTokenName() === 'T_CLASS') {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j]->getTokenName() === 'T_WHITESPACE') {
                        continue;
                    }

                    if ($tokens[$j]->getTokenName() === 'T_STRING') {
                        return $namespace . '\\' . $tokens[$j]->text;
                    } else {
                        break;
                    }
                }
            }
        }

        throw new \RuntimeException('Could not find class name in ' . $templateName);
    }
}
