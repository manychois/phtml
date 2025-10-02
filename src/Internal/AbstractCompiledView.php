<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use InvalidArgumentException;
use Manychois\Phtml\AbstractView;
use Manychois\Simdom\Element;
use Manychois\Simdom\Internal\DefaultHtmlSerialiser;

abstract class AbstractCompiledView extends AbstractView
{
    protected function setAttrValue(Element $element, string $name, mixed $value, bool $append): void
    {
        if (in_array($name, DefaultHtmlSerialiser::BOOLEAN_ATTRIBUTES)) {
            if ($value) {
                $element->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙SetAttr($name, '');
            } else {
                $element->removeAttr($name);
            }

            return;
        }

        if (null === $value) {
            if ($append) {
                return;
            }
            $element->removeAttr($name);

            return;
        }

        $old = $element->getAttr($name) ?? '';

        if (is_scalar($value)) {
            if (!is_string($value)) {
                $value = strval($value);
            }
            $element->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙SetAttr($name, $append ? $old . $value : $value);

            return;
        }

        throw new InvalidArgumentException('Unsupported value type: ' . get_debug_type($value));
    }
}
