<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Insert codes for HtmlTagHelper
 */

/** @var array<int,string> $tagNames */
$tagNames = [
    'a',
    'abbr',
    'address',
    'area',
    'article',
    'aside',
    'audio',
    'b',
    'base',
    'bdi',
    'bdo',
    'blockquote',
    'body',
    'br',
    'button',
    'canvas',
    'caption',
    'cite',
    'code',
    'col',
    'colgroup',
    'data',
    'datalist',
    'dd',
    'del',
    'details',
    'dfn',
    'dialog',
    'div',
    'dl',
    'dt',
    'em',
    'embed',
    'fieldset',
    'figcaption',
    'figure',
    'footer',
    'form',
    'h1',
    'h2',
    'h3',
    'h4',
    'h5',
    'h6',
    'head',
    'header',
    'hr',
    'html',
    'i',
    'iframe',
    'img',
    'input',
    'ins',
    'kbd',
    'label',
    'legend',
    'li',
    'link',
    'main',
    'map',
    'mark',
    'meta',
    'meter',
    'nav',
    'noscript',
    'object',
    'ol',
    'optgroup',
    'option',
    'output',
    'p',
    'param',
    'picture',
    'pre',
    'progress',
    'q',
    'rp',
    'rt',
    'ruby',
    's',
    'samp',
    'script',
    'section',
    'select',
    'slot',
    'small',
    'source',
    'span',
    'strong',
    'style',
    'sub',
    'summary',
    'sup',
    'table',
    'tbody',
    'td',
    'template',
    'textarea',
    'tfoot',
    'th',
    'thead',
    'time',
    'title',
    'tr',
    'track',
    'u',
    'ul',
    'var',
    'video',
    'wbr',
];
/** @var array<int,string> $voidTagNames */
$voidTagNames = [
    'area',
    'base',
    'br',
    'col',
    'embed',
    'hr',
    'img',
    'input',
    'link',
    'meta',
    'source',
    'track',
    'wbr',
];
/** @var array<int,string> $textTagNames */
$textTagNames = [
    'script',
    'style',
    'template',
    'textarea',
    'title',
];

$normalTemplate = <<<'PHP'
/**
 * Create %2$s `<%1$s>` element.
 *
 * @param array<string,bool|int|string|null>                   $attrs The attributes.
 * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
 *
 * @return Element The created `<%1$s>` element.
 *
 * @phpstan-param Content $inner
 */
public function %3$s(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
{
    return $this->createElement('%1$s', $attrs, $inner);
}
PHP;

$voidTemplate = <<<'PHP'
/**
 * Create %2$s `<%1$s>` element.
 *
 * @param array<string,bool|int|string|null> $attrs The attributes.
 *
 * @return Element The created `<%1$s>` element.
 */
public function %3$s(array $attrs = []): Element
{
    return $this->createElement('%1$s', $attrs);
}
PHP;


$textTemplate = <<<'PHP'
/**
 * Create %2$s `<%1$s>` element.
 *
 * @param array<string,bool|int|string|null> $attrs The attributes.
 * @param string                             $inner The inner content.
 *
 * @return Element The created `<%1$s>` element.
 */
public function %3$s(array $attrs = [], string $inner = ''): Element
{
    if ($inner === '') {
        $inner = null;
    }
    return $this->createElement('%1$s', $attrs, $inner);
}
PHP;

/**
 * Convert a tag name to a valid PHP function name.
 *
 * @param string $name The tag name.
 * @return string The function name.
 */
function toFuncName(string $name): string
{
    return \lcfirst(\str_replace('-', '', \ucwords($name, '-')));
}

function generateCode(
    array $tagNames,
    array $textTagNames,
    array $voidTagNames,
    string $normalTemplate,
    string $textTemplate,
    string $voidTemplate
): string {
    $allCode = '';
    foreach ($tagNames as $tagName) {
        $template = $normalTemplate;
        if (\in_array($tagName, $voidTagNames, true)) {
            $template = $voidTemplate;
        } elseif (\in_array($tagName, $textTagNames, true)) {
            $template = $textTemplate;
        }
        $article = \in_array($tagName[0], ['a', 'e', 'i', 'o', 'u'], true) ? 'an' : 'a';
        $code = \sprintf($template, $tagName, $article, toFuncName($tagName));
        $lines = \explode("\n", $code);
        $lines = \array_map(static fn($line) => $line === '' ? '' : "    {$line}", $lines);
        $code = \implode("\n", $lines);
        $allCode .= $code . "\n\n";
    }
    $allCode = "\n" . \trim($allCode, "\n") . "\n";
    return $allCode;
}

function injectCode(string $filePath, string $code): void
{
    $existingCode = \file_get_contents($filePath);
    \assert(\is_string($existingCode));
    $start = \strpos($existingCode, '#region auto generated code');
    \assert(\is_int($start));
    $start = \strpos($existingCode, "\n", $start) + 1;
    $end = \strpos($existingCode, '#endregion auto generated code', $start);
    \assert(\is_int($end));
    $end = \strrpos(\substr($existingCode, 0, $end), "\n");
    \assert(\is_int($end));

    $newCode = \substr_replace($existingCode, $code, $start, $end - $start);
    \file_put_contents($filePath, $newCode);
}

$code = generateCode($tagNames, $textTagNames, $voidTagNames, $normalTemplate, $textTemplate, $voidTemplate);
$filePath = __DIR__ . '/src/Utilities/HtmlElementHelper.php';
injectCode($filePath, $code);

/**
 * Insert codes for SvgTagHelper
 */

$tagNames = [
    'a',
    'altGlyph',
    'altGlyphDef',
    'altGlyphItem',
    'animate',
    'animateColor',
    'animateMotion',
    'animateTransform',
    'circle',
    'clipPath',
    'color-profile',
    'cursor',
    'defs',
    'desc',
    'ellipse',
    'feBlend',
    'feColorMatrix',
    'feComponentTransfer',
    'feComposite',
    'feConvolveMatrix',
    'feDiffuseLighting',
    'feDisplacementMap',
    'feDistantLight',
    'feFlood',
    'feFuncA',
    'feFuncB',
    'feFuncG',
    'feFuncR',
    'feGaussianBlur',
    'feImage',
    'feMerge',
    'feMergeNode',
    'feMorphology',
    'feOffset',
    'fePointLight',
    'feSpecularLighting',
    'feSpotLight',
    'feTile',
    'feTurbulence',
    'filter',
    'font',
    'font-face',
    'font-face-format',
    'font-face-name',
    'font-face-src',
    'font-face-uri',
    'foreignObject',
    'g',
    'glyph',
    'glyphRef',
    'hkern',
    'image',
    'line',
    'linearGradient',
    'marker',
    'mask',
    'metadata',
    'missing-glyph',
    'mpath',
    'path',
    'pattern',
    'polygon',
    'polyline',
    'radialGradient',
    'rect',
    'script',
    'set',
    'stop',
    'style',
    'svg',
    'switch',
    'symbol',
    'text',
    'textPath',
    'title',
    'tref',
    'tspan',
    'use',
    'view',
    'vkern',
];
$textTagNames = [
    'text',
    'title',
    'desc',
    'tref',
    'tspan',
];
$voidTagNames = [
    'circle',
    'ellipse',
    'line',
    'path',
    'polygon',
    'polyline',
    'rect',
    'stop',
];
$code = generateCode($tagNames, $textTagNames, $voidTagNames, $normalTemplate, $textTemplate, $voidTemplate);
$filePath = __DIR__ . '/src/Utilities/SvgElementHelper.php';
injectCode($filePath, $code);

/**
 * Insert codes for MathmlTagHelper
 */

$tagNames = [
    'annotation-xml',
    'annotation',
    'math',
    'merror',
    'mfrac',
    'mi',
    'mmultiscripts',
    'mn',
    'mo',
    'mover',
    'mpadded',
    'mphantom',
    'mprescripts',
    'mroot',
    'mrow',
    'ms',
    'mspace',
    'msqrt',
    'mstyle',
    'msub',
    'msubsup',
    'msup',
    'mtable',
    'mtd',
    'mtext',
    'mtr',
    'munder',
    'munderover',
    'semantics',
];
$textTagNames = [
    'annotation',
    'mi',
    'mn',
    'mo',
    'mtext',
];
$voidTagNames = [
    'merror',
    'mprescripts',
];
$code = generateCode($tagNames, $textTagNames, $voidTagNames, $normalTemplate, $textTemplate, $voidTemplate);
$filePath = __DIR__ . '/src/Utilities/MathmlElementHelper.php';
injectCode($filePath, $code);
