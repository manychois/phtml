<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\Element;
use Manychois\Simdom\Fragment;

final class PhpNode extends AbstractPhpNode
{
    // region extends AbstractPhpNode

    public function populate(Element $target, array $data, ?Element $host): ?AbstractNode
    {
        $fragment = Fragment::create();
        $childNodes = $target->childNodes->asArray();
        foreach ($childNodes as $child) {
            $fragment->appendChild($child);
        }
        if (0 === count($childNodes)) {
            $next = $target->nodeAfter;
        } else {
            $next = $childNodes[0];
        }
        $target->replaceWith($fragment);

        return $next;
    }

    // endregion extends AbstractPhpNode
}
