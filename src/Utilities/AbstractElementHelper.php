<?php

declare(strict_types=1);

namespace Manychois\Phtml\Utilities;

use Closure;
use Dom\DocumentFragment;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Dom\ParentNode;
use Dom\Text;
use Generator;
use Manychois\Phtml\AbstractView;

/**
 * Helper class for creating elements.
 *
 * @phpstan-type Content string|Node|iterable<string|Node|Closure|null>|Closure|null
 */
abstract class AbstractElementHelper
{
    private readonly AbstractView $view;

    /**
     * Initializes a new instance of a tag helper.
     *
     * @param AbstractView $view The view instance this helper attached to.
     */
    public function __construct(AbstractView $view)
    {
        $this->view = $view;
    }

    /**
     * Creates an element in the default namespace.
     *
     * @param string                                              $tag   The tag name.
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created element.
     *
     * @phpstan-param Content $inner
     */
    abstract public function createElement(
        string $tag,
        array $attrs = [],
        string|Node|iterable|Closure|null $inner = null
    ): Element;

    /**
     * Parses a partial HTML content.
     *
     * @param string $html    The HTML content.
     * @param string $context The context of the HTML content.
     *
     * @return DocumentFragment The docuemnt fragment that contains the parsed nodes.
     */
    public function parsePartial(string $html, string $context = 'body'): DocumentFragment
    {
        $element = $this->createElement($context);
        $element->innerHTML = $html;
        $fragment = $this->view->document->createDocumentFragment();
        while ($element->hasChildNodes()) {
            $node = $element->firstChild;
            \assert($node !== null);
            $element->removeChild($node);
            $fragment->append($node);
        }

        return $fragment;
    }

    /**
     * Parses the whole document.
     *
     * @param string $html The HTML content.
     *
     * @return Generator<int,Node> The direct child nodes of the document.
     */
    public function parseWholeDoc(string $html): Generator
    {
        $doc = HTMLDocument::createFromString($html);
        while ($doc->hasChildNodes()) {
            $node = $doc->firstChild;
            \assert($node !== null);
            $doc->removeChild($node);
            $node = $this->view->document->importNode($node, true);

            yield $node;
        }
    }

    /**
     * Create an element in the specified namespace.
     *
     * @param string|null                                         $namespaceUri The namespace URI.
     * @param string                                              $tag          The tag name.
     * @param array<string,bool|int|string|null>                  $attrs        The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner        The inner content.
     *
     * @return Element The created element.
     *
     * @phpstan-param Content $inner
     */
    protected function createElementNs(
        ?string $namespaceUri,
        string $tag,
        array $attrs = [],
        string|Node|iterable|Closure|null $inner = null
    ): Element {
        if ($namespaceUri === null) {
            $element = $this->view->document->createElement($tag);
        } else {
            $element = $this->view->document->createElementNS($namespaceUri, $tag);
        }
        foreach ($attrs as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            if ($value === true) {
                $value = '';
            }
            $element->setAttribute($name, \strval($value));
        }

        self::append($element, $inner);

        return $element;
    }

    /**
     * Appends child node(s) to a parent node.
     *
     * @param Node&ParentNode                   $parent The parent node.
     * @param string|Node|iterable|Closure|null $inner  The child node(s).
     *
     * @phpstan-param Content $inner
     */
    private function append(Node&ParentNode $parent, string|Node|iterable|Closure|null $inner): void
    {
        if ($inner === null) {
            return;
        }

        if (\is_string($inner)) {
            if ($parent->lastChild instanceof Text) {
                $parent->lastChild->data .= $inner;

                return;
            }

            $inner = $this->view->document->createTextNode($inner);
            $parent->append($inner);

            return;
        }

        if ($inner instanceof Node) {
            $parent->append($inner);

            return;
        }

        if (\is_iterable($inner)) {
            foreach ($inner as $child) {
                $this->append($parent, $child);
            }

            return;
        }

        /**
         * @var string|Node|iterable<string|Node|Closure|null>|Closure|null $result
         */
        $result = $inner->call($this->view, $this);
        $this->append($parent, $result);
    }
}
