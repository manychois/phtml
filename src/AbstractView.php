<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use Generator;
use InvalidArgumentException;
use LogicException;
use Manychois\Simdom\AbstractParentNode;
use Manychois\Simdom\Comment;
use Manychois\Simdom\Doctype;
use Manychois\Simdom\Element;
use Manychois\Simdom\Fragment;
use Manychois\Simdom\Text;

abstract class AbstractView
{
    private static int $idCounter = 0;
    private static string $prevNewId = '';
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

    protected static function newId(string $prefix = 'id-'): string
    {
        $id = $prefix . (++self::$idCounter);
        self::$prevNewId = $id;

        return $id;
    }

    protected static function prevId(): string
    {
        if ('' === self::$prevNewId) {
            throw new LogicException('No previous ID available. Call newId() first.');
        }

        return self::$prevNewId;
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
    final protected function renderRegion(string $name, array $props, array $regions): Generator
    {
        if (isset($regions[$name])) {
            $func = $regions[$name];
            if (!is_callable($func)) {
                throw new InvalidArgumentException(sprintf('Region %s is not callable: %s', $name, get_debug_type($func)));
            }

            yield from $this->resolveInner($func($props, $regions));
        }
    }

    final protected function appendInner(AbstractParentNode $parent, mixed $value): void
    {
        foreach ($this->resolveInner($value) as $node) {
            $parent->appendChild($node);
        }
    }

    /**
     * Recursively resolve a value into nodes.
     *
     * @return Generator<int,Doctype|Element|Text|Comment>
     */
    private function resolveInner(mixed $value): Generator
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
