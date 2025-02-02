<?php

namespace Manychois\Phtml;

class ViewData
{
    public readonly string $selfRef;
    /**
     * @var array<string,mixed>
     */
    private array $data;

    /**
     * @param array<string,mixed> $data
     */
    public function __construct(array $data, string $selfRef = 'self')
    {
       $this->selfRef = $selfRef;
       $this->data = $data;
    }

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
            if (!\array_key_exists($key, $value)) {
                return $fallback;
            }
            $value = $value[$key];
        }

        return $value;
    }

    public function getString(string $keyChain, string $fallback = ''): string
    {
        $value = $this->get($keyChain, $fallback);
        if (\is_string($value)) {
            return $value;
        }

        if ($value === null) {
            return '';
        }

        return \strval($value);
    }
}