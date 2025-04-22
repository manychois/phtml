<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use Closure;
use Dom\HTMLDocument;
use Dom\Node;
use Generator;
use Manychois\Phtml\Utilities\HtmlElementHelper;
use Manychois\Phtml\Utilities\MathmlElementHelper;
use Manychois\Phtml\Utilities\SvgElementHelper;
use TypeError;

/**
 * Represents a view that can be rendered into an HTML document.
 *
 * @phpstan-type Content string|Node|iterable<string|Node|Closure|null>|Closure|null
 */
abstract class AbstractView
{
    #region the following properties will be assigned during the rendering process.

    public ?self $parent = null;
    public ?self $child = null;
    protected HTMLDocument $document;
    protected ViewData $viewData;

    #endregion

    /**
     * Gets the main content of the child view.
     *
     * @param string|Node|iterable|Closure|null $default The default content.
     *
     * @return Generator<int,Node> The generated nodes.
     *
     * @phpstan-param Content $default
     */
    final public function content(string|Node|iterable|Closure|null $default = null): Generator
    {
        if ($this->child === null) {
            yield from $this->convertDefault($default);
        } else {
            yield from $this->child->render();
        }
    }

    /**
     * Gets the content of the specified region of the child view.
     *
     * @param string                            $name    The name of the region.
     * @param string|Node|iterable|Closure|null $default The default content.
     *
     * @return Generator<int,Node> The generated nodes.
     *
     * @phpstan-param Content $default
     */
    final public function region(string $name, string|Node|iterable|Closure|null $default = null): Generator
    {
        if ($this->child === null) {
            yield from $this->convertDefault($default);
        } else {
            yield from $this->child->renderRegion($name);
        }
    }

    /**
     * @internal
     *
     * Binds the view to the specified document and data during the rendering process.
     *
     * @param HTMLDocument      $doc       The document.
     * @param ViewData          $viewData  The view data.
     * @param AbstractView|null $childView The child view.
     */
    final public function bind(HTMLDocument $doc, ViewData $viewData, ?self $childView): void
    {
        $this->document = $doc;
        $this->viewData = $viewData;
        $this->child = $childView;
        if ($childView === null) {
            return;
        }

        $childView->parent = $this;
    }

    /**
     * Gets a helper for creating HTML elements.
     *
     * @return HtmlElementHelper The HTML element helper.
     */
    final protected function html(): HtmlElementHelper
    {
        return new HtmlElementHelper($this->document);
    }

    /**
     * Gets a helper for creating SVG elements.
     *
     * @return SvgElementHelper The SVG element helper.
     */
    final protected function svg(): SvgElementHelper
    {
        return new SvgElementHelper($this->document);
    }

    /**
     * Gets a helper for creating MathML elements.
     *
     * @return MathmlElementHelper The MathML element helper.
     */
    final protected function mathml(): MathmlElementHelper
    {
        return new MathmlElementHelper($this->document);
    }

    /**
     * Converts the default content into DOM nodes.
     *
     * @param string|Node|iterable|Closure|null $default The default content.
     *
     * @return Generator<int,Node> The generated nodes.
     *
     * @phpstan-param Content $default
     */
    final protected function convertDefault(string|Node|iterable|Closure|null $default = null): Generator
    {
        if ($default === null) {
            // do nothing
        } elseif (\is_string($default)) {
            yield $this->document->createTextNode($default);
        } elseif ($default instanceof Node) {
            yield $default;
        } elseif (\is_iterable($default)) {
            foreach ($default as $item) {
                yield from $this->convertDefault($item);
            }
        } else {
            $result = $default->call($this);
            if (
                $result !== null && !\is_string($result) && !($result instanceof Node)
                && !\is_iterable($result) && !($result instanceof Closure)
            ) {
                throw new TypeError(
                    \sprintf('Invalid default content. Found type: %s', \get_debug_type($result))
                );
            }

            // @phpstan-ignore argument.type
            yield from $this->convertDefault($result);
        }
    }

    /**
     * Renders the main content of the view.
     *
     * @return Generator<int,Node> The generated nodes.
     */
    abstract public function render(): Generator;

    /**
     * Returns the name of the parent view.
     *
     * @return string The name of the parent view, or an empty string if there is no parent view.
     */
    public function getParentName(): string
    {
        return '';
    }

    /**
     * Renders the content of the specified region.
     *
     * @param string $name The name of the region.
     *
     * @return Generator<int,Node> The generated nodes.
     */
    public function renderRegion(string $name): Generator
    {
        yield from [];
    }
}
