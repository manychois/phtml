<?php

declare(strict_types=1);

namespace Manychois\Phtml;

/**
 * Represents the context for rendering a view.
 * An application should have only one instance of this class.
 */
class ViewContext
{
    public readonly ViewResolverInterface $resolver;

    /**
     * Creates a new instance of ViewContext.
     *
     * @param ViewResolverInterface $resolver The view resolver.
     */
    public function __construct(ViewResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }
}
