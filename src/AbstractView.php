<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use Generator;
use InvalidArgumentException;
use LogicException;
use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\AbstractParentNode;
use Manychois\Simdom\Comment;
use Manychois\Simdom\Doctype;
use Manychois\Simdom\Element;
use Manychois\Simdom\Fragment;
use Manychois\Simdom\Internal\DefaultHtmlSerialiser;
use Manychois\Simdom\Text;
use ReflectionClass;

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
     * @param array<string,mixed> $props   The properties to pass to the view
     * @param mixed               $main    The main content to pass to the view
     * @param array<string,mixed> $regions The regions contents to pass to the view
     *
     * @return Generator<int,AbstractNode>
     */
    abstract public function render(array $props = [], mixed $main = null, array $regions = []): Generator;

    /**
     * Render the view and return as a single node.
     *
     * @param array<string,mixed> $props   The properties to pass to the view
     * @param mixed|null          $main    The main content to pass to the view
     * @param array<string,mixed> $regions The regions contents to pass to the view
     *
     * @throws InvalidArgumentException
     */
    final public function renderAsNode(array $props = [], mixed $main = null, array $regions = []): AbstractNode
    {
        $fragment = Fragment::create();
        $result = $this->render($props, $main, $regions);
        $this->appendInner($fragment, $result, $props, $main, $regions);
        if (1 === $fragment->childNodes->count()) {
            $onlyChild = $fragment->firstChild;
            assert(null !== $onlyChild);
            $onlyChild->remove();

            return $onlyChild;
        }

        return $fragment;
    }

    /**
     * Render the view based on the corresponding HTML file and return as a single node.
     *
     * @param array<string,mixed> $props   The properties to pass to the view
     * @param mixed|null          $main    The main content to pass to the view
     * @param array<string,mixed> $regions The regions contents to pass to the view
     *
     * @return AbstractNode The rendered view as a single node
     */
    final protected function fromHtmlView(array $props = [], mixed $main = null, array $regions = []): AbstractNode
    {
        $classFile = new ReflectionClass($this)->getFileName();
        assert(false !== $classFile);
        $htmlView = $this->engine->getView(basename($classFile, '.php'), true);

        return $htmlView->renderAsNode($props, $main, $regions);
    }

    final protected static function newId(string $prefix = 'id-'): string
    {
        $id = $prefix . (++self::$idCounter);
        self::$prevNewId = $id;

        return $id;
    }

    final protected static function prevId(): string
    {
        if ('' === self::$prevNewId) {
            throw new LogicException('No previous ID available. Call newId() first.');
        }

        return self::$prevNewId;
    }

    /**
     * Render a named region.
     *
     * @param string              $name    The name of the region to render
     * @param array<string,mixed> $props   The properties to pass to the region
     * @param mixed               $main    The main content to pass to the region
     * @param array<string,mixed> $regions The regions callables
     *
     * @return Generator<int,Doctype|Element|Text|Comment>
     */
    final protected function renderRegion(string $name, array $props, mixed $main, array $regions): Generator
    {
        if (isset($regions[$name])) {
            $region = $regions[$name];
            yield from $this->resolveInner($region, $props, $main, $regions);
        } elseif ('' === $name) {
            yield from $this->resolveInner($main, $props, $main, $regions);
        }
    }

    /**
     * Append resolved nodes to a parent node.
     *
     * @param AbstractParentNode  $parent  The parent node to append to
     * @param mixed               $value   The value to resolve and append
     * @param array<string,mixed> $props   The properties to pass to $value if it's a callable
     * @param array<string,mixed> $regions The regions callables to pass to $value if it's a callable
     */
    final protected function appendInner(AbstractParentNode $parent, mixed $value, array $props, mixed $main, array $regions): void
    {
        foreach ($this->resolveInner($value, $props, $main, $regions) as $node) {
            $parent->appendChild($node);
        }
    }

    final protected function setAttrValue(Element $element, string $name, mixed $value, bool $append): void
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
     * Recursively resolve a value into nodes.
     *
     * @param mixed               $value   The value to resolve
     * @param array<string,mixed> $props   The properties to pass to $value if it's a callable
     * @param mixed               $main    The main content to pass to $value if it's a callable
     * @param array<string,mixed> $regions The regions callables to pass to $value if it's a callable
     *
     * @return Generator<int,Doctype|Element|Text|Comment>
     */
    protected function resolveInner(mixed $value, array $props, mixed $main, array $regions): Generator
    {
        if (null === $value) {
            return false;
        }

        if (self::isRealNode($value)) {
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
            foreach ($value as $item) {
                if (self::isRealNode($item)) {
                    yield $i => $item;
                    ++$i;
                    continue;
                }
                foreach ($this->resolveInner($item, $props, $main, $regions) as $node) {
                    yield $i => $node;
                    ++$i;
                }
            }

            return $i > 0;
        }

        if (is_callable($value)) {
            $result = $value($props, $main, $regions);
            if (self::isRealNode($result)) {
                yield $result;
                return true;
            }
            
            $generator = $this->resolveInner($result, $props, $main, $regions);
            yield from $generator;
            return $generator->getReturn();
        }

        throw new InvalidArgumentException('Unsupported value type: ' . get_debug_type($value));
    }

    private static function isRealNode(mixed $value): bool
    {
        return $value instanceof Doctype
            || $value instanceof Element
            || $value instanceof Text
            || $value instanceof Comment;
    }
}
