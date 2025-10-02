<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use Generator;

abstract class AbstractView
{
    protected readonly ViewEngine $engine;

    public function __construct(ViewEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Render the view with the given properties and regions.
     *
     * @param array<string,mixed>    $props   The properties to pass to the view
     * @param array<string,callable> $regions The regions callables
     *
     * @return Generator<int,\Manychois\Simdom\AbstractNode>
     */
    abstract public function render(array $props = [], array $regions = []): Generator;
}
