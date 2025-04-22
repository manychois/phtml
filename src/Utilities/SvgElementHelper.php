<?php

declare(strict_types=1);

namespace Manychois\Phtml\Utilities;

use Closure;
use Dom\Element;
use Dom\Node;
use Manychois\Phtml\NamespaceUri;

/**
 * Helper class for creating elements in the SVG namespace.
 *
 * @phpstan-type Content string|Node|iterable<string|Node|Closure|null>|Closure|null
 */
class SvgElementHelper extends AbstractElementHelper
{
    /**
     * Creates an element in the SVG namespace.
     *
     * @param string                                              $tag   The tag name.
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created element.
     *
     * @phpstan-param Content $inner
     */
    public function createElement(
        string $tag,
        array $attrs = [],
        string|Node|iterable|Closure|null $inner = null
    ): Element {
        return $this->createElementNs(NamespaceUri::SVG, $tag, $attrs, $inner);
    }

    // @codeCoverageIgnoreStart

    #region auto generated code

    /**
     * Create an `<a>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<a>` element.
     *
     * @phpstan-param Content $inner
     */
    public function a(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('a', $attrs, $inner);
    }

    /**
     * Create an `<altGlyph>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<altGlyph>` element.
     *
     * @phpstan-param Content $inner
     */
    public function altGlyph(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('altGlyph', $attrs, $inner);
    }

    /**
     * Create an `<altGlyphDef>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<altGlyphDef>` element.
     *
     * @phpstan-param Content $inner
     */
    public function altGlyphDef(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('altGlyphDef', $attrs, $inner);
    }

    /**
     * Create an `<altGlyphItem>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<altGlyphItem>` element.
     *
     * @phpstan-param Content $inner
     */
    public function altGlyphItem(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('altGlyphItem', $attrs, $inner);
    }

    /**
     * Create an `<animate>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<animate>` element.
     *
     * @phpstan-param Content $inner
     */
    public function animate(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('animate', $attrs, $inner);
    }

    /**
     * Create an `<animateColor>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<animateColor>` element.
     *
     * @phpstan-param Content $inner
     */
    public function animateColor(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('animateColor', $attrs, $inner);
    }

    /**
     * Create an `<animateMotion>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<animateMotion>` element.
     *
     * @phpstan-param Content $inner
     */
    public function animateMotion(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('animateMotion', $attrs, $inner);
    }

    /**
     * Create an `<animateTransform>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<animateTransform>` element.
     *
     * @phpstan-param Content $inner
     */
    public function animateTransform(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('animateTransform', $attrs, $inner);
    }

    /**
     * Create a `<circle>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<circle>` element.
     */
    public function circle(array $attrs = []): Element
    {
        return $this->createElement('circle', $attrs);
    }

    /**
     * Create a `<clipPath>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<clipPath>` element.
     *
     * @phpstan-param Content $inner
     */
    public function clipPath(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('clipPath', $attrs, $inner);
    }

    /**
     * Create a `<color-profile>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<color-profile>` element.
     *
     * @phpstan-param Content $inner
     */
    public function colorProfile(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('color-profile', $attrs, $inner);
    }

    /**
     * Create a `<cursor>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<cursor>` element.
     *
     * @phpstan-param Content $inner
     */
    public function cursor(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('cursor', $attrs, $inner);
    }

    /**
     * Create a `<defs>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<defs>` element.
     *
     * @phpstan-param Content $inner
     */
    public function defs(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('defs', $attrs, $inner);
    }

    /**
     * Create a `<desc>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<desc>` element.
     */
    public function desc(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('desc', $attrs, $inner);
    }

    /**
     * Create an `<ellipse>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<ellipse>` element.
     */
    public function ellipse(array $attrs = []): Element
    {
        return $this->createElement('ellipse', $attrs);
    }

    /**
     * Create a `<feBlend>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feBlend>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feBlend(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feBlend', $attrs, $inner);
    }

    /**
     * Create a `<feColorMatrix>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feColorMatrix>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feColorMatrix(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feColorMatrix', $attrs, $inner);
    }

    /**
     * Create a `<feComponentTransfer>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feComponentTransfer>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feComponentTransfer(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feComponentTransfer', $attrs, $inner);
    }

    /**
     * Create a `<feComposite>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feComposite>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feComposite(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feComposite', $attrs, $inner);
    }

    /**
     * Create a `<feConvolveMatrix>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feConvolveMatrix>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feConvolveMatrix(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feConvolveMatrix', $attrs, $inner);
    }

    /**
     * Create a `<feDiffuseLighting>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feDiffuseLighting>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feDiffuseLighting(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feDiffuseLighting', $attrs, $inner);
    }

    /**
     * Create a `<feDisplacementMap>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feDisplacementMap>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feDisplacementMap(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feDisplacementMap', $attrs, $inner);
    }

    /**
     * Create a `<feDistantLight>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feDistantLight>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feDistantLight(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feDistantLight', $attrs, $inner);
    }

    /**
     * Create a `<feFlood>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feFlood>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feFlood(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feFlood', $attrs, $inner);
    }

    /**
     * Create a `<feFuncA>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feFuncA>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feFuncA(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feFuncA', $attrs, $inner);
    }

    /**
     * Create a `<feFuncB>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feFuncB>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feFuncB(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feFuncB', $attrs, $inner);
    }

    /**
     * Create a `<feFuncG>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feFuncG>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feFuncG(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feFuncG', $attrs, $inner);
    }

    /**
     * Create a `<feFuncR>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feFuncR>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feFuncR(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feFuncR', $attrs, $inner);
    }

    /**
     * Create a `<feGaussianBlur>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feGaussianBlur>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feGaussianBlur(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feGaussianBlur', $attrs, $inner);
    }

    /**
     * Create a `<feImage>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feImage>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feImage(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feImage', $attrs, $inner);
    }

    /**
     * Create a `<feMerge>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feMerge>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feMerge(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feMerge', $attrs, $inner);
    }

    /**
     * Create a `<feMergeNode>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feMergeNode>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feMergeNode(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feMergeNode', $attrs, $inner);
    }

    /**
     * Create a `<feMorphology>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feMorphology>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feMorphology(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feMorphology', $attrs, $inner);
    }

    /**
     * Create a `<feOffset>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feOffset>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feOffset(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feOffset', $attrs, $inner);
    }

    /**
     * Create a `<fePointLight>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<fePointLight>` element.
     *
     * @phpstan-param Content $inner
     */
    public function fePointLight(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('fePointLight', $attrs, $inner);
    }

    /**
     * Create a `<feSpecularLighting>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feSpecularLighting>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feSpecularLighting(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feSpecularLighting', $attrs, $inner);
    }

    /**
     * Create a `<feSpotLight>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feSpotLight>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feSpotLight(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feSpotLight', $attrs, $inner);
    }

    /**
     * Create a `<feTile>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feTile>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feTile(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feTile', $attrs, $inner);
    }

    /**
     * Create a `<feTurbulence>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<feTurbulence>` element.
     *
     * @phpstan-param Content $inner
     */
    public function feTurbulence(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('feTurbulence', $attrs, $inner);
    }

    /**
     * Create a `<filter>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<filter>` element.
     *
     * @phpstan-param Content $inner
     */
    public function filter(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('filter', $attrs, $inner);
    }

    /**
     * Create a `<font>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<font>` element.
     *
     * @phpstan-param Content $inner
     */
    public function font(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('font', $attrs, $inner);
    }

    /**
     * Create a `<font-face>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<font-face>` element.
     *
     * @phpstan-param Content $inner
     */
    public function fontFace(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('font-face', $attrs, $inner);
    }

    /**
     * Create a `<font-face-format>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<font-face-format>` element.
     *
     * @phpstan-param Content $inner
     */
    public function fontFaceFormat(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('font-face-format', $attrs, $inner);
    }

    /**
     * Create a `<font-face-name>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<font-face-name>` element.
     *
     * @phpstan-param Content $inner
     */
    public function fontFaceName(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('font-face-name', $attrs, $inner);
    }

    /**
     * Create a `<font-face-src>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<font-face-src>` element.
     *
     * @phpstan-param Content $inner
     */
    public function fontFaceSrc(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('font-face-src', $attrs, $inner);
    }

    /**
     * Create a `<font-face-uri>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<font-face-uri>` element.
     *
     * @phpstan-param Content $inner
     */
    public function fontFaceUri(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('font-face-uri', $attrs, $inner);
    }

    /**
     * Create a `<foreignObject>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<foreignObject>` element.
     *
     * @phpstan-param Content $inner
     */
    public function foreignObject(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('foreignObject', $attrs, $inner);
    }

    /**
     * Create a `<g>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<g>` element.
     *
     * @phpstan-param Content $inner
     */
    public function g(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('g', $attrs, $inner);
    }

    /**
     * Create a `<glyph>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<glyph>` element.
     *
     * @phpstan-param Content $inner
     */
    public function glyph(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('glyph', $attrs, $inner);
    }

    /**
     * Create a `<glyphRef>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<glyphRef>` element.
     *
     * @phpstan-param Content $inner
     */
    public function glyphRef(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('glyphRef', $attrs, $inner);
    }

    /**
     * Create a `<hkern>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<hkern>` element.
     *
     * @phpstan-param Content $inner
     */
    public function hkern(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('hkern', $attrs, $inner);
    }

    /**
     * Create an `<image>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<image>` element.
     *
     * @phpstan-param Content $inner
     */
    public function image(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('image', $attrs, $inner);
    }

    /**
     * Create a `<line>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<line>` element.
     */
    public function line(array $attrs = []): Element
    {
        return $this->createElement('line', $attrs);
    }

    /**
     * Create a `<linearGradient>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<linearGradient>` element.
     *
     * @phpstan-param Content $inner
     */
    public function linearGradient(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('linearGradient', $attrs, $inner);
    }

    /**
     * Create a `<marker>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<marker>` element.
     *
     * @phpstan-param Content $inner
     */
    public function marker(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('marker', $attrs, $inner);
    }

    /**
     * Create a `<mask>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mask>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mask(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mask', $attrs, $inner);
    }

    /**
     * Create a `<metadata>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<metadata>` element.
     *
     * @phpstan-param Content $inner
     */
    public function metadata(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('metadata', $attrs, $inner);
    }

    /**
     * Create a `<missing-glyph>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<missing-glyph>` element.
     *
     * @phpstan-param Content $inner
     */
    public function missingGlyph(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('missing-glyph', $attrs, $inner);
    }

    /**
     * Create a `<mpath>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mpath>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mpath(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mpath', $attrs, $inner);
    }

    /**
     * Create a `<path>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<path>` element.
     */
    public function path(array $attrs = []): Element
    {
        return $this->createElement('path', $attrs);
    }

    /**
     * Create a `<pattern>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<pattern>` element.
     *
     * @phpstan-param Content $inner
     */
    public function pattern(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('pattern', $attrs, $inner);
    }

    /**
     * Create a `<polygon>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<polygon>` element.
     */
    public function polygon(array $attrs = []): Element
    {
        return $this->createElement('polygon', $attrs);
    }

    /**
     * Create a `<polyline>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<polyline>` element.
     */
    public function polyline(array $attrs = []): Element
    {
        return $this->createElement('polyline', $attrs);
    }

    /**
     * Create a `<radialGradient>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<radialGradient>` element.
     *
     * @phpstan-param Content $inner
     */
    public function radialGradient(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('radialGradient', $attrs, $inner);
    }

    /**
     * Create a `<rect>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<rect>` element.
     */
    public function rect(array $attrs = []): Element
    {
        return $this->createElement('rect', $attrs);
    }

    /**
     * Create a `<script>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<script>` element.
     *
     * @phpstan-param Content $inner
     */
    public function script(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('script', $attrs, $inner);
    }

    /**
     * Create a `<set>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<set>` element.
     *
     * @phpstan-param Content $inner
     */
    public function set(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('set', $attrs, $inner);
    }

    /**
     * Create a `<stop>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<stop>` element.
     */
    public function stop(array $attrs = []): Element
    {
        return $this->createElement('stop', $attrs);
    }

    /**
     * Create a `<style>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<style>` element.
     *
     * @phpstan-param Content $inner
     */
    public function style(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('style', $attrs, $inner);
    }

    /**
     * Create a `<svg>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<svg>` element.
     *
     * @phpstan-param Content $inner
     */
    public function svg(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('svg', $attrs, $inner);
    }

    /**
     * Create a `<switch>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<switch>` element.
     *
     * @phpstan-param Content $inner
     */
    public function switch(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('switch', $attrs, $inner);
    }

    /**
     * Create a `<symbol>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<symbol>` element.
     *
     * @phpstan-param Content $inner
     */
    public function symbol(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('symbol', $attrs, $inner);
    }

    /**
     * Create a `<text>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<text>` element.
     */
    public function text(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('text', $attrs, $inner);
    }

    /**
     * Create a `<textPath>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<textPath>` element.
     *
     * @phpstan-param Content $inner
     */
    public function textPath(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('textPath', $attrs, $inner);
    }

    /**
     * Create a `<title>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<title>` element.
     */
    public function title(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('title', $attrs, $inner);
    }

    /**
     * Create a `<tref>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<tref>` element.
     */
    public function tref(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('tref', $attrs, $inner);
    }

    /**
     * Create a `<tspan>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<tspan>` element.
     */
    public function tspan(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('tspan', $attrs, $inner);
    }

    /**
     * Create an `<use>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<use>` element.
     *
     * @phpstan-param Content $inner
     */
    public function use(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('use', $attrs, $inner);
    }

    /**
     * Create a `<view>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<view>` element.
     *
     * @phpstan-param Content $inner
     */
    public function view(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('view', $attrs, $inner);
    }

    /**
     * Create a `<vkern>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<vkern>` element.
     *
     * @phpstan-param Content $inner
     */
    public function vkern(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('vkern', $attrs, $inner);
    }

    #endregion auto generated code

    // @codeCoverageIgnoreEnd
}
