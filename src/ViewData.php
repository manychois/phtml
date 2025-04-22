<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use InvalidArgumentException;
use Stringable;
use TypeError;

/**
 * Represents the data passed to a view during rendering.
 */
class ViewData
{
    public readonly string $selfRef;
    /**
     * @var array<string,mixed>
     */
    private array $data;

    /**
     * Creates a new instance of ViewData.
     *
     * @param array<string,mixed> $data    The data to be passed to the view.
     * @param string              $selfRef The key name which represents the view data itself.
     */
    public function __construct(array $data, string $selfRef = 'self')
    {
        $this->selfRef = $selfRef;
        $this->data = $data;
    }

    /**
     * Gets the value associated with the specified key chain.
     *
     * @param string $keyChain The key chain to retrieve, separated by dots.
     * @param mixed  $fallback The fallback value if the key chain does not exist.
     *
     * @return mixed The value associated with the key chain, or the fallback value.
     */
    public function get(string $keyChain, mixed $fallback = null): mixed
    {
        $keys = \explode('.', $keyChain);
        $count = \count($keys);
        $value = $this->data;
        for ($i = 0; $i < $count; $i++) {
            $key = $keys[$i];
            if ($i === 0 && $key === $this->selfRef) {
                continue;
            }
            if (!\is_array($value)) {
                throw new TypeError(
                    \sprintf('Expected an array at key "%s", but got %s.', $keys[$i - 1], \get_debug_type($value))
                );
            }
            if (!\array_key_exists($key, $value)) {
                return $fallback;
            }
            $value = $value[$key];
        }

        return $value;
    }

    /**
     * Gets the value associated with the specified key chain as a string.
     *
     * @param string $keyChain The key chain to retrieve, separated by dots.
     * @param string $fallback The fallback value if the key chain does not exist.
     *
     * @return string The value associated with the key chain as a string, or the fallback value.
     */
    public function getString(string $keyChain, string $fallback = ''): string
    {
        $value = $this->get($keyChain, $fallback);
        if (\is_string($value)) {
            return $value;
        }

        if ($value === null) {
            return '';
        }

        if ($value instanceof Stringable) {
            return $value->__toString();
        }

        if (\is_scalar($value)) {
            return \strval($value);
        }

        throw new TypeError(
            \sprintf('Failed to convert value to string. Found type: %s', \get_debug_type($value))
        );
    }

    /**
     * Gets the value associated with the specified key chain as an object.
     *
     * @template T of object
     *
     * @param string          $keyChain The key chain to retrieve, separated by dots.
     * @param class-string<T> $class    The class name of the expected object type.
     *
     * @return T The value associated with the key chain as an object.
     */
    public function getObject(string $keyChain, string $class): object
    {
        $value = $this->get($keyChain, null);
        if (\is_object($value) && $value instanceof $class) {
            return $value;
        }

        throw new TypeError(\sprintf('Expected an object, but got %s.', \get_debug_type($value)));
    }

    /**
     * Sets the value associated with the specified key chain.
     *
     * @param string $keyChain The key chain to set, separated by dots.
     * @param mixed  $value    The value to set.
     */
    public function set(string $keyChain, mixed $value): void
    {
        $keys = \explode('.', $keyChain);
        $count = \count($keys);
        if ($count === 0) {
            throw new InvalidArgumentException('Key chain cannot be empty.');
        }

        $lastKey = \array_pop($keys);
        $target = &$this->data;
        foreach ($keys as $key) {
            if (!\array_key_exists($key, $target)) {
                $target[$key] = [];
            } elseif (!\is_array($target[$key])) {
                throw new TypeError(
                    \sprintf('Expected an array at key "%s", but got %s.', $key, \get_debug_type($target[$key]))
                );
            }
            $target = &$target[$key];
        }
        $target[$lastKey] = $value;
    }
}
