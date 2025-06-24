<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Manychois\Phtml\View;
use Manychois\Phtml\ViewEngine;
use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\Element;

final class ViewNode extends AbstractPhpNode
{
    private readonly ViewEngine $viewEngine;

    public function __construct(ViewEngine $viewEngine)
    {
        $this->viewEngine = $viewEngine;
    }

    // region extends AbstractPhpNode

    public function populate(Element $target, array $data, ?Element $host): ?AbstractNode
    {
        $viewName = substr($target->name, 5);
        $view = $this->viewEngine->convertToView($viewName);

        assert(null === $host);
        $next = View::findNodeAfter($target, $target);
        $data['hostElement'] = $target;
        $fragment = $view->render($data, $target);
        $target->replaceWith($fragment);

        return $next;
    }

    // endregion extends AbstractPhpNode
}
