<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Manychois\Phtml\View;
use Manychois\Phtml\ViewEngine;
use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\Element;
use Manychois\Simdom\Fragment;
use RuntimeException;
use Traversable;

final class PhpForeach extends AbstractPhpNode
{
    private static int $id = 1;
    private readonly ViewEngine $viewEngine;

    public function __construct(ViewEngine $viewEngine)
    {
        $this->viewEngine = $viewEngine;
    }

    // region extends AbstractPhpNode

    public function populate(Element $target, array $data, ?Element $host): ?AbstractNode
    {
        $next = View::findNodeAfter($target, $target);

        $forExpr = trim($target->getAttr('php:foreach') ?? '');
        $matched = \preg_match('/^(.*)\s+as\s+(\$(\S+)\s*=>\s*)?\$(\S+)$/', $forExpr, $matches);
        if (1 !== $matched) {
            throw new RuntimeException("Invalid php:for expression: '{$forExpr}'");
        }
        $collectionExpr = $matches[1];
        $keyVarName = $matches[3];
        $valueVarName = $matches[4];

        $viewName = '(php:foreach)#' . self::$id++;
        $cloned = $target->clone(true);
        $cloned->removeAttr('php:foreach');
        $definition = Fragment::create();
        $definition->appendChild($cloned);
        $view = new View($viewName, $this->viewEngine, $definition);
        $collection = $this->viewEngine->evaluateExpr($collectionExpr, $data);

        if (!is_array($collection) && !$collection instanceof Traversable) {
            throw new RuntimeException('The collection in php:foreach must be an array or Traversable, got ' . get_debug_type($collection));
        }

        foreach ($collection as $key => $value) {
            if ('' !== $keyVarName) {
                $data[$keyVarName] = $key;
            }
            $data[$valueVarName] = $value;
            $rendered = $view->render($data, null);
            $target->before($rendered);
        }
        $target->remove();

        return $next;
    }

    // endregion extends AbstractPhpNode
}
