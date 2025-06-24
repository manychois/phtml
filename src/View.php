<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use InvalidArgumentException;
use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\AbstractParentNode;
use Manychois\Simdom\Comment;
use Manychois\Simdom\Element;
use Manychois\Simdom\Fragment;
use Manychois\Simdom\Text;
use Stringable;

class View
{
    public readonly string $name;
    protected readonly ViewEngine $viewEngine;
    private readonly Fragment $definition;

    public function __construct(string $name, ViewEngine $viewEngine, Fragment $definition)
    {
        $this->name = $name;
        $this->viewEngine = $viewEngine;
        $this->definition = $definition;
    }

    public static function findNodeAfter(AbstractNode $current, ?AbstractParentNode $skip): ?AbstractNode
    {
        $after = $current->nodeAfter;
        if (null === $skip) {
            return $after;
        }

        while (null !== $after && $skip->contains($after)) {
            $after = $after->nodeAfter;
        }

        return $after;
    }

    /**
     * Renders the view with the provided data and host element.
     *
     * @param array<string,mixed> $data
     * @param Element|null        $host the host element, if any, where this view is being rendered
     *
     * @return Fragment the rendered fragment containing the view's content
     */
    public function render(array $data, ?Element $host): Fragment
    {
        $cloned = $this->definition->clone(true);
        $next = $cloned;
        while (true) {
            $next = $this->populateNode($next, $data, $host);
            if (null === $next) {
                break;
            }
        }

        return $cloned;
    }

    /**
     * Populates the provided node with data, handling different node types.
     *
     * @param AbstractNode        $node the node to populate
     * @param array<string,mixed> $data the data array containing values to populate the node
     * @param Element|null        $host the host element, if any, where this node is being populated
     *
     * @return AbstractNode|null the next node to process after populating the current node, or null if there are no more nodes
     */
    protected function populateNode(AbstractNode $node, array $data, ?Element $host): ?AbstractNode
    {
        if ($node instanceof Element) {
            return $this->populateElement($node, $data, $host);
        }

        if ($node instanceof Comment || $node instanceof Text) {
            $this->populateCharacterData($node, $data);
        }

        return self::findNodeAfter($node, null);
    }

    /**
     * Populates the character data (Comment or Text) with the provided data.
     *
     * @param Comment|Text        $target the target character data to populate
     * @param array<string,mixed> $data   the data array containing values to populate the character data
     */
    protected function populateCharacterData(Comment|Text $target, array $data): void
    {
        $text = $target->data;
        $text = preg_replace_callback('/\{\{(.*?)\}\}/', function ($matches) use ($data) {
            $matched = trim($matches[1]);
            if ('' === $matched) {
                return '';
            }

            $evaluated = $this->viewEngine->evaluateExpr($matched, $data);
            if (is_scalar($evaluated)) {
                return strval($evaluated);
            }
            if ($evaluated instanceof Stringable) {
                return $evaluated->__toString();
            }
            throw new InvalidArgumentException('Invalid expression result: ' . get_debug_type($evaluated) . ' for expression: ' . $matched);
        }, $text);
        assert(is_string($text));

        if ($target instanceof Comment) {
            $text = str_replace('-->', '--&gt;', $text);
        }
        $target->data = $text;
    }

    /**
     * Populates the target element with the provided data.
     *
     * @param Element             $target the target element to populate
     * @param array<string,mixed> $data   the data array containing values to populate the element
     * @param Element|null        $host   the host element, if any, where this target is being populated
     *
     * @return AbstractNode|null the next node to process after populating the target, or null if there are no more nodes
     */
    protected function populateElement(Element $target, array $data, ?Element $host): ?AbstractNode
    {
        if ($target->hasAttr('php:foreach')) {
            $phpFor = new Internal\PhpForeach($this->viewEngine);

            return $phpFor->populate($target, $data, $host);
        }

        if ($target->hasAttr('php:if')) {
            $elseConditions = [];
            $next = $target->nextElementSibling;
            while ($next instanceof Element && ($next->hasAttr('php:elseif') || $next->hasAttr('php:else'))) {
                $elseConditions[] = $next;
                if ($next->hasAttr('php:else')) {
                    break;
                }
                $next = $next->nextElementSibling;
            }
            $phpIf = new Internal\PhpIf($this->viewEngine, $elseConditions);

            return $phpIf->populate($target, $data, $host);
        }

        if (str_starts_with($target->name, 'view:')) {
            $viewNode = new Internal\ViewNode($this->viewEngine);

            return $viewNode->populate($target, $data, null);
        }

        $node = match ($target->name) {
            'php:const' => new Internal\PhpConst($this->viewEngine),
            'php:in' => new Internal\PhpIn(),
            'php:node' => new Internal\PhpNode(),
            default => null,
        };

        $this->populateAttrs($target, $data);

        return $node?->populate($target, $data, $host) ?? self::findNodeAfter($target, null);
    }

    /**
     * Populates attributes of the target element based on the provided data.
     *
     * @param Element             $target the target element whose attributes will be populated
     * @param array<string,mixed> $data   the data array containing values to populate the attributes
     */
    protected function populateAttrs(Element $target, array $data): void
    {
        $toRemove = [];
        foreach ($target->attrs() as $key => $value) {
            if ('php:class' === $key) {
                $value = $this->viewEngine->evaluateExpr($value, $data);
                $target->className = trim($target->className . ' ' . $this->toClassName($value));
                $toRemove[] = $key;
            }
        }
        foreach ($toRemove as $key) {
            $target->removeAttr($key);
        }
    }

    protected function toClassName(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        if (is_scalar($value)) {
            return strval($value);
        }

        if (is_object($value)) {
            if ($value instanceof Stringable) {
                return $value->__toString();
            }

            throw new InvalidArgumentException('Cannot convert object to string: ' . get_debug_type($value));
        }

        if (is_array($value)) {
            $classNames = [];
            if (array_is_list($value)) {
                foreach ($value as $item) {
                    $classNames[] = $this->toClassName($item);
                }
            } else {
                foreach ($value as $key => $item) {
                    if ($item) {
                        $classNames[] = $key;
                    }
                }
            }

            return implode(' ', $classNames);
        }

        throw new InvalidArgumentException('Unsupported type for class name conversion: ' . get_debug_type($value));
    }
}
