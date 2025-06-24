<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\Element;

final class PhpIn extends AbstractPhpNode
{
    // region extends AbstractPhpNode

    public function populate(Element $target, array $data, ?Element $host): ?AbstractNode
    {
        $next = $target->nextSibling;
        if (null === $next) {
            $next = $target->parent?->nextSibling;
        }
        if (null === $host) {
            $childNodes = $target->childNodes->asArray();
            $target->replaceWith(...$childNodes);
            if (count($childNodes) > 0) {
                $next = $childNodes[0];
            }
        } else {
            $contentNodes = $this->getPhpOut($host, $target->getAttr('name') ?? '');
            $target->replaceWith(...$contentNodes);
            if (count($contentNodes) > 0) {
                $next = $contentNodes[0];
            }
        }

        return $next;
    }

    // endregion extends AbstractPhpNode

    /**
     * @return array<AbstractNode>
     */
    protected function getPhpOut(Element $host, string $name): array
    {
        $childNodes = [];
        foreach ($host->childNodes as $node) {
            if ($node instanceof Element) {
                if ('php:out' === $node->name) {
                    $targetName = $node->getAttr('name') ?? '';
                    if ($targetName === $name) {
                        foreach ($node->childNodes as $child) {
                            $childNodes[] = $child->clone(true);
                        }
                    }
                    continue;
                }
            }

            if ('' === $name) {
                $childNodes[] = $node->clone(true);
            }
        }

        return $childNodes;
    }
}
