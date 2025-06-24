<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Manychois\Phtml\View;
use Manychois\Phtml\ViewEngine;
use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\Element;

final class PhpIf extends AbstractPhpNode
{
    private readonly ViewEngine $viewEngine;
    /**
     * @var array<Element>
     */
    private array $elseConditions = [];

    /**
     * @param array<Element> $elseConditions
     */
    public function __construct(ViewEngine $viewEngine, array $elseConditions = [])
    {
        $this->elseConditions = $elseConditions;
        $this->viewEngine = $viewEngine;
    }

    // region extends AbstractPhpNode

    public function populate(Element $target, array $data, ?Element $host): ?AbstractNode
    {
        $ifExpr = trim($target->getAttr('php:if') ?? '');
        $ifResult = $this->viewEngine->evaluateExpr($ifExpr, $data);
        $found = null;
        if ($ifResult) {
            $target->removeAttr('php:if');
            $found = $target;
        }

        foreach ($this->elseConditions as $i => $condition) {
            if (null === $found) {
                if ($condition->hasAttr('php:elseif')) {
                    $elseIfExpr = trim($condition->getAttr('php:elseif') ?? '');
                    $elseIfResult = $this->viewEngine->evaluateExpr($elseIfExpr, $data);
                    if ($elseIfResult) {
                        $found = $condition;
                    }
                } elseif ($condition->hasAttr('php:else')) {
                    $found = $condition;
                }
            }
            $condition->removeAttr('php:elseif');
            $condition->removeAttr('php:else');
        }

        $allConditions = array_merge([$target], $this->elseConditions);
        if ($found) {
            $next = $found; // re-enter the element for further processing
            foreach ($allConditions as $condition) {
                if ($condition !== $found) {
                    $condition->remove();
                }
            }
        } else {
            $last = end($allConditions);
            $next = View::findNodeAfter($last, $last);
            foreach ($allConditions as $condition) {
                $condition->remove();
            }
        }

        return $next;
    }

    // endregion extends AbstractPhpNode
}
