<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Generator;
use InvalidArgumentException;
use Manychois\Phtml\AbstractView;
use Manychois\Simdom\AbstractParentNode;
use Manychois\Simdom\Comment;
use Manychois\Simdom\Doctype;
use Manychois\Simdom\Element;
use Manychois\Simdom\Fragment;
use Manychois\Simdom\Internal\DefaultHtmlSerialiser;
use Manychois\Simdom\Text;

abstract class AbstractCompiledView extends AbstractView
{
    protected function appendInner(AbstractParentNode $parent, mixed $value): void
    {
        foreach ($this->resolveInner($value) as $node) {
            $parent->appendChild($node);
        }
    }

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

    /**
     * Render a named region.
     *
     * @param string                 $name    The name of the region to render
     * @param array<string,mixed>    $props   The properties to pass to the region
     * @param array<string,callable> $regions The regions callables
     *
     * @return Generator<int,Doctype|Element|Text|Comment>
     */
    protected function renderRegion(string $name, array $props, array $regions): Generator
    {
        if (isset($regions[$name])) {
            $func = $regions[$name];
            if (!is_callable($func)) {
                throw new InvalidArgumentException(sprintf('Region %s is not callable: %s', $name, get_debug_type($func)));
            }

            yield from $this->resolveInner($func($props, $regions));
        }
    }

    /**
     * Recursively resolve a value into nodes.
     *
     * @return Generator<int,Doctype|Element|Text|Comment>
     */
    protected function resolveInner(mixed $value): Generator
    {
        if (null === $value) {
            return false;
        }

        if ($value instanceof Doctype || $value instanceof Element || $value instanceof Text || $value instanceof Comment) {
            yield $value;

            return true;
        }

        if ($value instanceof Fragment) {
            $children = $value->childNodes->asArray();
            $value->childNodes->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Clear();
            yield from $children;

            return count($children) > 0;
        }

        if (is_scalar($value)) {
            if (!is_string($value)) {
                $value = strval($value);
            }
            yield Text::create($value);

            return true;
        }

        if (is_iterable($value)) {
            $i = 0;
            $hasContent = false;
            foreach ($value as $item) {
                foreach ($this->resolveInner($item) as $node) {
                    yield $i => $node;
                    ++$i;
                    $hasContent = true;
                }
            }

            return $hasContent;
        }

        throw new InvalidArgumentException('Unsupported value type: ' . get_debug_type($value));
    }
}
