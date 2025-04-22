<?php

declare(strict_types=1);

namespace Manychois\Phtml\Utilities;

use Closure;
use Dom\Element;
use Dom\Node;
use Manychois\Phtml\NamespaceUri;

/**
 * Helper class for creating elements in the MahtML namespace.
 *
 * @phpstan-type Content string|Node|iterable<string|Node|Closure|null>|Closure|null
 */
class MathmlElementHelper extends AbstractElementHelper
{
    /**
     * Creates an element in the MahtML namespace.
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
        return $this->createElementNs(NamespaceUri::MATHML, $tag, $attrs, $inner);
    }

    // @codeCoverageIgnoreStart

    #region auto generated code

    /**
     * Create an `<annotation-xml>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<annotation-xml>` element.
     *
     * @phpstan-param Content $inner
     */
    public function annotationXml(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('annotation-xml', $attrs, $inner);
    }

    /**
     * Create an `<annotation>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<annotation>` element.
     */
    public function annotation(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('annotation', $attrs, $inner);
    }

    /**
     * Create a `<math>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<math>` element.
     *
     * @phpstan-param Content $inner
     */
    public function math(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('math', $attrs, $inner);
    }

    /**
     * Create a `<merror>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<merror>` element.
     */
    public function merror(array $attrs = []): Element
    {
        return $this->createElement('merror', $attrs);
    }

    /**
     * Create a `<mfrac>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mfrac>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mfrac(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mfrac', $attrs, $inner);
    }

    /**
     * Create a `<mi>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<mi>` element.
     */
    public function mi(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('mi', $attrs, $inner);
    }

    /**
     * Create a `<mmultiscripts>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mmultiscripts>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mmultiscripts(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mmultiscripts', $attrs, $inner);
    }

    /**
     * Create a `<mn>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<mn>` element.
     */
    public function mn(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('mn', $attrs, $inner);
    }

    /**
     * Create a `<mo>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<mo>` element.
     */
    public function mo(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('mo', $attrs, $inner);
    }

    /**
     * Create a `<mover>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mover>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mover(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mover', $attrs, $inner);
    }

    /**
     * Create a `<mpadded>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mpadded>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mpadded(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mpadded', $attrs, $inner);
    }

    /**
     * Create a `<mphantom>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mphantom>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mphantom(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mphantom', $attrs, $inner);
    }

    /**
     * Create a `<mprescripts>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     *
     * @return Element The created `<mprescripts>` element.
     */
    public function mprescripts(array $attrs = []): Element
    {
        return $this->createElement('mprescripts', $attrs);
    }

    /**
     * Create a `<mroot>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mroot>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mroot(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mroot', $attrs, $inner);
    }

    /**
     * Create a `<mrow>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mrow>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mrow(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mrow', $attrs, $inner);
    }

    /**
     * Create a `<ms>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<ms>` element.
     *
     * @phpstan-param Content $inner
     */
    public function ms(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('ms', $attrs, $inner);
    }

    /**
     * Create a `<mspace>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mspace>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mspace(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mspace', $attrs, $inner);
    }

    /**
     * Create a `<msqrt>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<msqrt>` element.
     *
     * @phpstan-param Content $inner
     */
    public function msqrt(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('msqrt', $attrs, $inner);
    }

    /**
     * Create a `<mstyle>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mstyle>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mstyle(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mstyle', $attrs, $inner);
    }

    /**
     * Create a `<msub>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<msub>` element.
     *
     * @phpstan-param Content $inner
     */
    public function msub(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('msub', $attrs, $inner);
    }

    /**
     * Create a `<msubsup>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<msubsup>` element.
     *
     * @phpstan-param Content $inner
     */
    public function msubsup(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('msubsup', $attrs, $inner);
    }

    /**
     * Create a `<msup>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<msup>` element.
     *
     * @phpstan-param Content $inner
     */
    public function msup(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('msup', $attrs, $inner);
    }

    /**
     * Create a `<mtable>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mtable>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mtable(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mtable', $attrs, $inner);
    }

    /**
     * Create a `<mtd>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mtd>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mtd(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mtd', $attrs, $inner);
    }

    /**
     * Create a `<mtext>` element.
     *
     * @param array<string,bool|int|string|null> $attrs The attributes.
     * @param string                             $inner The inner content.
     *
     * @return Element The created `<mtext>` element.
     */
    public function mtext(array $attrs = [], string $inner = ''): Element
    {
        if ($inner === '') {
            $inner = null;
        }

        return $this->createElement('mtext', $attrs, $inner);
    }

    /**
     * Create a `<mtr>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<mtr>` element.
     *
     * @phpstan-param Content $inner
     */
    public function mtr(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('mtr', $attrs, $inner);
    }

    /**
     * Create a `<munder>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<munder>` element.
     *
     * @phpstan-param Content $inner
     */
    public function munder(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('munder', $attrs, $inner);
    }

    /**
     * Create a `<munderover>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<munderover>` element.
     *
     * @phpstan-param Content $inner
     */
    public function munderover(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('munderover', $attrs, $inner);
    }

    /**
     * Create a `<semantics>` element.
     *
     * @param array<string,bool|int|string|null>                  $attrs The attributes.
     * @param string|Node|iterable<string|Node|null>|Closure|null $inner The inner content.
     *
     * @return Element The created `<semantics>` element.
     *
     * @phpstan-param Content $inner
     */
    public function semantics(array $attrs = [], string|Node|iterable|Closure|null $inner = null): Element
    {
        return $this->createElement('semantics', $attrs, $inner);
    }

    #endregion auto generated code

    // @codeCoverageIgnoreEnd
}
