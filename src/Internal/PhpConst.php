<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Manychois\Phtml\View;
use Manychois\Phtml\ViewEngine;
use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\Element;
use Manychois\Simdom\Fragment;

final class PhpConst extends AbstractPhpNode
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

        $fragment = Fragment::create();
        $childNodes = $target->childNodes->asArray();
        foreach ($childNodes as $child) {
            $fragment->appendChild($child);
        }
        $viewName = '(php:const)#' . self::$id++;
        $view = new View($viewName, $this->viewEngine, $fragment);

        foreach ($target->attrs() as $name => $value) {
            $nameParts = explode('-', $name);
            $phpVarName = lcfirst(implode('', array_map('ucfirst', $nameParts)));
            $data[$phpVarName] = $this->viewEngine->evaluateExpr($value, $data);
        }
        $rendered = $view->render($data, null);
        $target->replaceWith($rendered);

        return $next;
    }

    // endregion extends AbstractPhpNode
}
