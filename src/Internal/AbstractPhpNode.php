<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\Element;

abstract class AbstractPhpNode
{
    /**
     * Populates the target element with data and returns the next node to process.
     *
     * @param Element             $target the target element to populate
     * @param array<string,mixed> $data   the data to use for populating the target
     * @param Element|null        $host   the host element, if any, where this node is being rendered
     *
     * @return AbstractNode|null the next node to process after populating the target, or null if there are no more nodes
     */
    abstract public function populate(Element $target, array $data, ?Element $host): ?AbstractNode;
}
