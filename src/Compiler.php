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
        require_once $templateFile;
        $templateClass = $this->getFullClassName($templateFile);
        $template = new $templateClass();
        \assert($template instanceof AbstractTemplate);

        $templateChain = [$template];
        while ($template->parent() !== '') {
            $templateFile = $this->findTemplateFile($template->parent());
            require_once $templateFile;
            $templateClass = $this->getFullClassName($templateFile);
            $template = new $templateClass();
            \assert($template instanceof AbstractTemplate);
            $templateChain[] = $template;
        }
        $templateChain = \array_reverse($templateChain);

        $doc = HTMLDocument::createEmpty();
        $doctype = $doc->implementation->createDocumentType('html', '', '');
        $doctype = $doc->importNode($doctype, true);
        $doc->appendChild($doctype);

        $templateCount = \count($templateChain);
        for ($i = 0; $i < $templateCount; ++$i) {
            $template = $templateChain[$i];
            if ($i === 0) {
                $content = $template->content($doc, $data);
                if ($content instanceof Element || $content instanceof Comment) {
                    $doc->appendChild($content);
                } elseif ($content instanceof DocumentFragment) {
                    foreach ($content->childNodes as $child) {
                        if ($child instanceof Element || $child instanceof Comment) {
                            $doc->appendChild($child);
                        }
                    }
                }
            } else {
                $phpBody = $doc->querySelector('php-body');
                if ($phpBody !== null) {
                    $content = $template->content($doc, $data);
                    $phpBody->replaceWith($content);
                }
            }
        }
        // clear unresolved php-body, php-region
        do {
            $phpBody = $doc->querySelector('php-body');
            if ($phpBody !== null) {
                $temp = $doc->createDocumentFragment();
                $temp->append(...$phpBody->childNodes);
                $phpBody->replaceWith($temp);
            }
        } while ($phpBody !== null);
        do {
            $phpRegion = $doc->querySelector('php-region');
            if ($phpRegion !== null) {
                $temp = $doc->createDocumentFragment();
                $temp->append(...$phpRegion->childNodes);
                $phpRegion->replaceWith($temp);
            }
        } while ($phpRegion !== null);

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

    private function findTemplateFile(string $templateName): string
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
