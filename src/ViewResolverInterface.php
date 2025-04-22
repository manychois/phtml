<?php

declare(strict_types=1);

namespace Manychois\Phtml;

/**
 * Resolves a view by its name.
 */
interface ViewResolverInterface
{
    /**
     * Resolves a view by its name.
     *
     * @param string $viewName The name of the view.
     *
     * @return AbstractView The resolved view.
     */
    public function resolve(string $viewName): AbstractView;
}
