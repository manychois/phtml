<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use Dom\HTMLDocument;

/**
 * Renders a view with the specified data.
 */
class Renderer
{
    public readonly ViewContext $context;

    /**
     * Creates a new instance of Renderer.
     *
     * @param ViewContext $context The view context.
     */
    public function __construct(ViewContext $context)
    {
        $this->context = $context;
    }

    /**
     * Renders a view with the specified data.
     *
     * @param string   $viewName The name of the view.
     * @param ViewData $viewData The data for the view.
     *
     * @return HTMLDocument The rendered HTML document.
     */
    public function render(string $viewName, ViewData $viewData): HTMLDocument
    {
        $doc = HTMLDocument::createEmpty();
        $view = $this->context->resolver->resolve($viewName);
        $view->bind($doc, $viewData, null);
        $hierarchy = [$view];
        $parentName = $view->getParentName();
        while ($parentName !== '') {
            $parentView = $this->context->resolver->resolve($parentName);
            $parentView->bind($doc, $viewData, $view);
            $view = $parentView;
            \array_unshift($hierarchy, $view);
            $parentName = $view->getParentName();
        }
        foreach (\array_reverse($hierarchy) as $v) {
            $v->preRender();
        }
        foreach ($view->render() as $node) {
            $doc->appendChild($node);
        }
        foreach ($hierarchy as $v) {
            $v->postRender();
        }
        foreach ($hierarchy as $v) {
            $v->parent = null;
            $v->child = null;
        }

        return $doc;
    }
}
