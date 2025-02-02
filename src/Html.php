<?php

namespace Manychois\Phtml;

use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Dom\ParentNode;
use Generator;

class Html
{
    public readonly HTMLDocument $doc;

    public function __construct(HTMLDocument $doc)
    {
        $this->doc = $doc;
    }

    public function parsePartial(string $html, string $context = 'body'): Node
    {
        $contextElement = $this->doc->createElement($context);
        $contextElement->innerHTML = $html;
        if ($contextElement->childNodes->length === 1) {
            $single = $contextElement->firstChild;
            \assert($single !== null);
            $contextElement->removeChild($single);
            return $single;
        }

        $fragment = $this->doc->createDocumentFragment();
        foreach ($contextElement->childNodes as $child) {
            $fragment->appendChild($child);
        }
        return $fragment;
    }

    public function parsePartialAsElement(string $html, string $context = 'body'): Element
    {
        $node = $this->parsePartial($html, $context);
        if ($node instanceof Element) {
            return $node;
        }
        throw new \RuntimeException('Expected an Element');
    }

    public function traverse(Node $node, bool $includeSelf = false): Generator
    {
        if ($includeSelf) {
            yield $node;
        }
        if ($node instanceof ParentNode) {
            foreach ($node->childNodes as $child) {
                yield from $this->traverse($child, true);
            }
        }
    }
}
