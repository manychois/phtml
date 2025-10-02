<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

final class StringUtility
{
    public static function kebabToCamel(string $str): string
    {
        return lcfirst(implode(array_map('ucfirst', explode('-', $str))));
    }

    public static function toPascal(string $str): string
    {
        $kebab = self::toKebabCase($str);

        return ucfirst(self::kebabToCamel($kebab));
    }

    public static function toKebabCase(string $str): string
    {
        $temp = preg_replace('/([a-z])([A-Z])/', '$1-$2', $str);
        assert(is_string($temp));
        $temp = strtolower($temp);
        $kebab = preg_replace('/[^a-z0-9]+/', '-', $temp);
        assert(is_string($kebab));

        return $kebab;
    }
}
