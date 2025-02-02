<?php

namespace Manychois\Phtml;

use Dom\Attr;
use Dom\CharacterData;
use Dom\DocumentFragment;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Dom\ParentNode;
use Dom\Text;

abstract class AbstractTemplate
{
    private const string TEXT_REGEX = '/\$\{(\w+(\.\w+)*)\}/';
    private const string ATTR_NAME_REGEX = '/^\$\{(\w+(\.\w+)*)\}$/';

    abstract public function content(HTMLDocument $doc, ViewData $vd): ?Node;
    abstract public function parent(): string;

    protected function parseHtmlFile(HTMLDocument $doc, string $filename, string $context = 'body'): Node
    {
        $raw = \file_get_contents($filename);
        $html = new Html($doc);
        return $html->parsePartial($raw, $context);
    }

    protected function interpolate(Node $node, ViewData $vd): void
    {
        $replacer = static function (string $text) use ($vd) {
            return \preg_replace_callback(self::TEXT_REGEX, static function (array $matches) use ($vd) {
                return $vd->getString($matches[1]);
            }, $text);
        };
        if ($node instanceof CharacterData) {
            $node->textContent = $replacer($node->textContent);
        } elseif ($node instanceof Element) {
            $toReplace = [];
            foreach ($node->attributes as $attr) {
                \assert($attr instanceof Attr);
                $attr->value = $replacer($attr->value);
                if (\preg_match(self::ATTR_NAME_REGEX, $attr->name)) {
                    $toReplace[] = $attr;
                    $node->removeAttributeNode($attr);
                }
            }
            foreach ($toReplace as $attr) {
                $node->setAttribute($replacer($attr->name), $attr->value);
            }
            foreach ($node->childNodes as $child) {
                $this->interpolate($child, $vd);
            }
        } elseif ($node instanceof ParentNode) {
            foreach ($node->childNodes as $child) {
                $this->interpolate($child, $vd);
            }
        }
    }

    protected function replaceText(ParentNode $context, string $selector, string $placeholder, string $replacement): void
    {
        foreach ($context->querySelectorAll($selector) as $element) {
            \assert($element instanceof Element);
            foreach ($element->childNodes as $node) {
                if ($node instanceof Text) {
                    $node->textContent = \str_replace($placeholder, $replacement, $node->textContent);
                }
            }
        }
    }

    protected function replaceAttr(ParentNode $context, string $selector, string $placeholder, string $replacement): void
    {
        foreach ($context->querySelectorAll($selector) as $element) {
            \assert($element instanceof Element);
            $toReplace = [];
            foreach ($element->attributes as $attr) {
                \assert($attr instanceof Attr);
                if ($attr->name === $placeholder) {
                    $element->removeAttributeNode($attr);
                    $toReplace[] = $attr;
                } else {
                    $attr->value = \str_replace($placeholder, $replacement, $attr->value);
                }
            }
            foreach ($toReplace as $attr) {
                $element->setAttribute($replacement, \str_replace($placeholder, $replacement, $attr->value));
            }
        }
    }
}
