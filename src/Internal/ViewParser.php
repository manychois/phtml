<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

use Generator;
use Manychois\Phtml\ViewEngine;
use Manychois\Simdom\AbstractNode;
use Manychois\Simdom\AbstractParentNode;
use Manychois\Simdom\Comment;
use Manychois\Simdom\Doctype;
use Manychois\Simdom\Element;
use Manychois\Simdom\Fragment;
use Manychois\Simdom\HtmlParser;
use Manychois\Simdom\Text;
use RuntimeException;

class ViewParser
{
    private readonly ViewEngine $engine;
    /**
     * @var array<string,int>
     */
    private static array $varNames = [];

    protected static function var(string $prefix): string
    {
        if (!array_key_exists($prefix, self::$varNames)) {
            self::$varNames[$prefix] = 0;
        }
        $id = ++self::$varNames[$prefix];

        return '$acv' . \ucfirst($prefix) . $id;
    }

    public function __construct(ViewEngine $engine)
    {
        $this->engine = $engine;
    }

    public function parse(string $viewName, string $source, string $filePath): string
    {
        $className = StringUtility::toPascal($viewName) . 'View';
        $htmlParser = new HtmlParser();
        $fragment = $htmlParser->parseFragment($source);
        foreach ($fragment->descendants() as $child) {
            if ($child instanceof Text) {
                if ('' === trim($child->data)) {
                    if (str_contains($child->data, "\n")) {
                        $child->data = "\n";
                    } else {
                        $child->data = ' ';
                    }
                }
            }
        }

        $lines = [];
        foreach ($this->codeChildNodes($fragment, '        ', '', '$props', '$main', '$regions') as $line) {
            $lines[] = $line;
        }

        $code = <<<'PHP'
            <?php

            namespace Manychois\Phtml\Internal\Compiled;

            use Generator;
            use Manychois\Phtml\AbstractView;
            use Manychois\Phtml\Internal\AbstractCompiledView;
            use Manychois\Phtml\Internal\ViewHelper;
            use Manychois\Simdom\Comment;
            use Manychois\Simdom\Doctype;
            use Manychois\Simdom\Element;
            use Manychois\Simdom\Fragment;
            use Manychois\Simdom\Text;

            final class %s extends \%s
            {
                public function render(array $props = [], mixed $main = null, array $regions = []): Generator
                {
                    extract($props, \EXTR_SKIP);
            %s
                }
            }
            PHP;

        $code = sprintf($code, $className, $this->engine->config->baseClass, implode("\n", $lines));
        file_put_contents($filePath, $code);
        require_once $filePath;

        return '\Manychois\Phtml\Internal\Compiled\\' . $className;
    }

    private function codeChildNodes(AbstractParentNode $parent, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $node = $parent->firstChild;
        while (null !== $node) {
            yield from $this->codeNode($node, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
            $node = $node->nextSibling;
        }
    }

    private function codeNode(AbstractNode $node, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        if ($node instanceof Element) {
            yield from $this->codeElement($node, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        }
        if ($node instanceof Text) {
            yield from $this->codeText($node, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        }
        if ($node instanceof Comment) {
            yield from $this->codeComment($node, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        }
        if ($node instanceof Doctype) {
            yield from $this->codeDoctype($node, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        }
    }

    private function codeDoctype(Doctype $doctype, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $code = sprintf(
            'Doctype::𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Create(%s, %s, %s)',
            $this->toPhpLiteral($doctype->name),
            $this->toPhpLiteral($doctype->publicId),
            $this->toPhpLiteral($doctype->systemId),
        );
        if ('' === $parentVar) {
            yield $indent . sprintf('yield %s;', $code);
        } else {
            yield $indent . sprintf('%s->childNodes->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Append(%s);', $parentVar, $code);
        }
    }

    private function codeComment(Comment $comment, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $code = sprintf(
            'Comment::𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Create(%s)',
            $this->toPhpLiteral($comment->data),
        );
        if ('' === $parentVar) {
            yield $indent . sprintf('yield %s;', $code);
        } else {
            yield $indent . sprintf('%s->childNodes->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Append(%s);', $parentVar, $code);
        }
    }

    private function codeElement(Element $element, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        if ($element->hasAttr('php-foreach')) {
            yield from $this->codeForeachElement($element, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        } elseif ($element->hasAttr('php-if')) {
            yield from $this->codeIfElement($element, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        } elseif ($element->hasAttr('php-else') || $element->hasAttr('php-elseif')) {
            throw new RuntimeException('php-else and php-elseif must be preceded by an element with php-if.');
        } elseif ('php-element' === $element->name) {
            yield from $this->codePhpElement($element, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        } elseif ('php-view-in' === $element->name) {
            yield from $this->codeViewInElement($element, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        } elseif ('php-view-out' === $element->name) {
            // Handled in codeCustomElement
            throw new RuntimeException('php-view-out must be a child of a custom element.');
        } elseif ('php-var' === $element->name) {
            yield from $this->codePhpVar($element, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        } elseif ($this->engine->hasView($element->name)) {
            yield from $this->codeCustomElement($element, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        } else {
            yield from $this->codeOrdinaryElement($element, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);
        }
    }

    private function codePhpVar(Element $element, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $oldPropsVar = $this->var('oldProps');
        yield $indent . sprintf('%s = [];', $oldPropsVar);

        $codeVar = function (string $key, string $value) use ($indent, $propsVar, $oldPropsVar): Generator {
            $kl = $this->toPhpLiteral($key);
            yield $indent . sprintf('if (array_key_exists(%s, %s)) {', $kl, $propsVar);
            yield $indent . sprintf('    %s[%s] = %s[%s];', $oldPropsVar, $kl, $propsVar, $kl);
            yield $indent . '}';
            yield $indent . sprintf('%s[%s] = %s;', $propsVar, $kl, $value);
            yield $indent . sprintf('$%s = %s;', $key, $value);
        };

        $attrs = $element->attrs();
        foreach ($attrs as $key => $value) {
            if (!str_starts_with($key, '$')) {
                continue;
            }
            $key = StringUtility::slugToCamel(substr($key, 1));
            $expr = trim($value);
            yield from $codeVar($key, $expr);
        }

        yield from $this->codeChildNodes($element, $indent, $parentVar, $propsVar, $mainVar, $regionsVar);

        foreach ($attrs as $key => $value) {
            if (!str_starts_with($key, '$')) {
                continue;
            }
            $key = StringUtility::slugToCamel(substr($key, 1));
            $kl = $this->toPhpLiteral($key);
            yield $indent . sprintf('if (array_key_exists(%s, %s)) {', $kl, $oldPropsVar);
            yield $indent . sprintf('    %s[%s] = %s[%s];', $propsVar, $kl, $oldPropsVar, $kl);
            yield $indent . sprintf('    $%s = %s[%s];', $key, $oldPropsVar, $kl);
            yield $indent . '} else {';
            yield $indent . sprintf('    unset(%s[%s]);', $propsVar, $kl);
            yield $indent . sprintf('    unset($%s);', $key);
            yield $indent . '}';
        }
    }

    private function codePhpElement(Element $element, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $elementVar = self::var('element');
        $elementNameVar = self::var('elementName');
        if ($element->hasAttr('$name')) {
            yield $indent . sprintf('%s = %s;', $elementNameVar, $element->getAttr('$name'));
        } else {
            yield $indent . sprintf('%s = %s;', $elementNameVar, $this->toPhpLiteral($element->getAttr('name') ?? ''));
        }
        $element->removeAttr('name');
        $element->removeAttr('$name');

        yield $indent . sprintf('%s = %s === \'\' ? Fragment::create() : Element::create(%s);', $elementVar, $elementNameVar, $elementNameVar);

        $attrCodes = \iterator_to_array($this->codeAttrs($element, $indent . '    ', $elementVar));
        if (count($attrCodes) > 0) {
            yield $indent . sprintf('if (%s !== \'\') {', $elementNameVar);
            yield from $attrCodes;
            yield $indent . '}';
        }

        yield from $this->codeChildNodes($element, $indent, $elementVar, $propsVar, $mainVar, $regionsVar);

        if ('' === $parentVar) {
            $tempVar = self::var('temp');
            yield $indent . sprintf('if (%s === \'\') {', $elementNameVar);
            yield $indent . '    ' . sprintf('%s = %s->childNodes->asArray();', $tempVar, $elementVar);
            yield $indent . '    ' . sprintf('%s->childNodes->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Clear();', $elementVar);
            yield $indent . '    ' . sprintf('yield from %s;', $tempVar);
            yield $indent . '} else {';
            yield $indent . '    ' . sprintf('yield %s;', $elementVar);
            yield $indent . '}';
        } else {
            yield $indent . sprintf('$this->appendInner(%s, %s, %s, %s, %s);', $parentVar, $elementVar, $propsVar, $mainVar, $regionsVar);
        }
    }

    private function codeForeachElement(Element $element, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $foreachExpr = $element->getAttr('php-foreach') ?? '';
        if (1 !== \preg_match('/^(.+)\s+as\s+((\$\w+)\s*=>\s*)?(\$\w+)$/', trim($foreachExpr), $matches)) {
            throw new RuntimeException('Invalid php-foreach expression: ' . $foreachExpr);
        }

        $keyExpr = $matches[3];
        $valueVar = $matches[4];
        $loopPropsVar = self::var('loopProps');
        yield $indent . sprintf('foreach (%s) {', $foreachExpr);
        yield $indent . sprintf('    %s = %s;', $loopPropsVar, $propsVar);
        if ('' !== $keyExpr) {
            yield $indent . sprintf('    %s[\'%s\'] = %s;', $loopPropsVar, substr($keyExpr, 1), $keyExpr);
        }
        yield $indent . sprintf('    %s[\'%s\'] = %s;', $loopPropsVar, substr($valueVar, 1), $valueVar);
        $element->removeAttr('php-foreach');
        yield from $this->codeElement($element, $indent . '    ', $parentVar, $loopPropsVar, $mainVar, $regionsVar);
        yield $indent . '}';
    }

    private function codeIfElement(Element $element, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $ifExpr = $element->getAttr('php-if');
        yield $indent . sprintf('if (%s) {', $ifExpr);
        $element->removeAttr('php-if');
        yield from $this->codeElement($element, $indent . '    ', $parentVar, $propsVar, $mainVar, $regionsVar);

        $next = $element->nextElementSibling;
        while (null !== $next && ($next->hasAttr('php-elseif') || $next->hasAttr('php-else'))) {
            $element = $next;
            $next = $next->nextElementSibling;

            if ($element->hasAttr('php-elseif')) {
                $elseifExpr = $element->getAttr('php-elseif');
                yield $indent . sprintf('} elseif (%s) {', $elseifExpr);
                $element->removeAttr('php-elseif');
                yield from $this->codeElement($element, $indent . '    ', $parentVar, $propsVar, $mainVar, $regionsVar);
                $element->remove();
            } else {
                yield $indent . '} else {';
                $element->removeAttr('php-else');
                yield from $this->codeElement($element, $indent . '    ', $parentVar, $propsVar, $mainVar, $regionsVar);
                $element->remove();
                break;
            }
        }

        yield $indent . '}';
    }

    private function codeCustomElement(Element $element, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $viewVar = self::var('view');
        $viewPropsVar = self::var('viewProps');
        $viewRegionsVar = self::var('viewRegions');
        $viewName = $element->name;
        yield $indent . sprintf('%s = $this->engine->getView(%s);', $viewVar, $this->toPhpLiteral($viewName));

        yield $indent . sprintf('%s = %s;', $viewPropsVar, $propsVar);
        $attrs = $element->attrs();
        foreach ($attrs as $key => $value) {
            if (str_starts_with($key, '$')) {
                continue;
            }
            $key = StringUtility::slugToCamel($key);

            if ('' === $value) {
                yield $indent . sprintf('%s[%s] = \'\';', $viewPropsVar, $this->toPhpLiteral($key));
            } else {
                $data = $value;
                $strExprs = [];
                while ('' !== $data) {
                    $hasExpr = 1 === preg_match('/{{\s*\S+\s*}}/', $data, $matches, \PREG_OFFSET_CAPTURE);
                    if ($hasExpr) {
                        $pos = (int) $matches[0][1];
                        if ($pos > 0) {
                            $strExprs[] = $this->toPhpLiteral(substr($data, 0, $pos));
                            $data = substr($data, $pos + 2);
                        } else {
                            $data = substr($data, 2);
                        }

                        $pos = strpos($data, '}}');
                        assert(false !== $pos);
                        $expr = trim(substr($data, 0, $pos));
                        $strExprs[] = sprintf('strval(%s)', $expr);
                        $data = substr($data, $pos + 2);
                    } else {
                        $strExprs[] = $this->toPhpLiteral($data);
                        $data = '';
                    }
                }
                yield $indent . sprintf('%s[%s] = %s;', $viewPropsVar, $this->toPhpLiteral($key), implode(' . ', $strExprs));
            }
            unset($attrs[$key]);
        }

        foreach ($attrs as $key => $value) {
            if (!str_starts_with($key, '$')) {
                continue;
            }
            $key = StringUtility::slugToCamel(substr($key, 1));
            $expr = trim($value);
            yield $indent . sprintf('%s[%s] = %s;', $viewPropsVar, $this->toPhpLiteral($key), $expr);
            unset($attrs[$key]);
        }

        yield $indent . sprintf('%s = [];', $viewRegionsVar);
        $regions = ['' => Fragment::create()];
        foreach ($element->childNodes as $child) {
            if ($child instanceof Text && '' === trim($child->data)) {
                continue;
            }

            $regionName = '';
            if ($child instanceof Element && 'php-view-out' === $child->name) {
                $regionName = $child->getAttr('name') ?? '';
                if (!array_key_exists($regionName, $regions)) {
                    $regions[$regionName] = Fragment::create();
                }
                foreach ($child->childNodes as $grandChild) {
                    if ($grandChild instanceof Text && '' === trim($grandChild->data)) {
                        continue;
                    }
                    $regions[$regionName]->appendChild($grandChild);
                }
            } else {
                $regions[$regionName]->appendChild($child);
            }
        }

        $viewMainVar = $this->var('viewMain');
        $hasViewMain = false;
        foreach ($regions as $name => $fragment) {
            if (0 === $fragment->childNodes->count()) {
                continue;
            }
            if ('' === $name) {
                $hasViewMain = true;
                yield $indent . sprintf('%s = function (array $props, mixed $main, array $regions): Generator {', $viewMainVar);
                yield $indent . '    extract($props, \EXTR_SKIP);';
                yield from $this->codeChildNodes($fragment, $indent . '    ', '', '$props', '$main', '$regions');
                yield $indent . '};';
            } else {
                yield $indent . sprintf('%s[\'%s\'] = function (array $props, mixed $main, array $regions): Generator {', $viewRegionsVar, $name);
                yield $indent . '    extract($props, \EXTR_SKIP);';
                yield from $this->codeChildNodes($fragment, $indent . '    ', '', '$props', '$main', '$regions');
                yield $indent . '};';
            }
        }
        if (!$hasViewMain) {
            yield $indent . sprintf('%s = null;', $viewMainVar);
        }

        if ('' === $parentVar) {
            yield $indent . sprintf('yield from %s->render(%s, %s, %s);', $viewVar, $viewPropsVar, $viewMainVar, $viewRegionsVar);
        } else {
            yield $indent . sprintf('$this->appendInner(%s, %s->render(%s, %s, %s), %s, %s, %s);', $parentVar, $viewVar, $viewPropsVar, $viewMainVar, $viewRegionsVar, $propsVar, $mainVar, $regionsVar);
        }
    }

    private function codeViewInElement(Element $element, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $name = $element->getAttr('name') ?? '';
        $code = sprintf('$this->renderRegion(%s, %s, %s, %s)', $this->toPhpLiteral($name), $propsVar, $mainVar, $regionsVar);
        if ($element->childNodes->count() > 0) {
            $hasRegionVar = self::var('hasRegion');
            yield $indent . sprintf('%s = false;', $hasRegionVar);
            $nodeVar = self::var('node');
            yield $indent . sprintf('foreach (%s as %s) {', $code, $nodeVar);
            yield $indent . sprintf('    %s = true;', $hasRegionVar);
            if ('' !== $parentVar) {
                yield $indent . sprintf('    %s->childNodes->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Append(%s);', $parentVar, $nodeVar);
            } else {
                yield $indent . sprintf('    yield %s;', $nodeVar);
            }
            yield $indent . '}';

            yield $indent . sprintf('if (!%s) {', $hasRegionVar);
            yield from $this->codeChildNodes($element, $indent . '    ', $parentVar, $propsVar, $mainVar, $regionsVar);
            yield $indent . '}';
        } else {
            if ('' === $parentVar) {
                yield $indent . sprintf('yield from %s;', $code);
            } else {
                yield $indent . sprintf('$this->appendInner(%s, %s, %s, %s, %s);', $parentVar, $code, $propsVar, $mainVar, $regionsVar);
            }
        }
    }

    private function codeOrdinaryElement(Element $element, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $parts = \preg_split('/[^A-Za-z]+/', $element->name, -1, \PREG_SPLIT_NO_EMPTY);
        assert(false !== $parts);
        $parts = array_map(fn ($s) => \ucfirst($s), $parts);
        $elementVar = self::var(\lcfirst(implode('', $parts)));

        yield $indent . sprintf('%s = Element::𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Create(%s);', $elementVar, $this->toPhpLiteral($element->name));

        yield from $this->codeAttrs($element, $indent, $elementVar);

        yield from $this->codeChildNodes($element, $indent, $elementVar, $propsVar, $mainVar, $regionsVar);

        if ('' === $parentVar) {
            yield $indent . sprintf('yield %s;', $elementVar);
        } else {
            yield $indent . sprintf('%s->childNodes->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Append(%s);', $parentVar, $elementVar);
        }
    }

    private function codeAttrs(Element $element, string $indent, string $elementVar): Generator
    {
        $attrs = $element->attrs();
        foreach ($attrs as $key => $value) {
            if (str_starts_with($key, '$')) {
                continue;
            }
            if ('' === $value) {
                yield $indent . sprintf('$this->setAttrValue(%s, %s, \'\', false);', $elementVar, $this->toPhpLiteral($key));
            } else {
                $data = $value;
                $isFirst = true;
                while ('' !== $data) {
                    $hasExpr = 1 === preg_match('/{{\s*\S+\s*}}/', $data, $matches, \PREG_OFFSET_CAPTURE);
                    if ($hasExpr) {
                        $pos = (int) $matches[0][1];
                        if ($pos > 0) {
                            $temp = $this->toPhpLiteral(substr($data, 0, $pos));
                            yield $indent . sprintf('$this->setAttrValue(%s, %s, %s, %s);', $elementVar, $this->toPhpLiteral($key), $temp, $isFirst ? 'false' : 'true');
                            $data = substr($data, $pos + 2);
                            $isFirst = false;
                        } else {
                            $data = substr($data, 2);
                        }

                        $pos = strpos($data, '}}');
                        assert(false !== $pos);
                        $expr = trim(substr($data, 0, $pos));
                        yield $indent . sprintf('$this->setAttrValue(%s, %s, %s, %s);', $elementVar, $this->toPhpLiteral($key), $expr, $isFirst ? 'false' : 'true');
                        $data = substr($data, $pos + 2);
                    } else {
                        yield $indent . sprintf('$this->setAttrValue(%s, %s, %s, %s);', $elementVar, $this->toPhpLiteral($key), $this->toPhpLiteral($data), $isFirst ? 'false' : 'true');
                        $data = '';
                    }
                    $isFirst = false;
                }
            }
            unset($attrs[$key]);
        }

        foreach ($attrs as $key => $value) {
            if (!str_starts_with($key, '$')) {
                continue;
            }
            $key = substr($key, 1);
            $expr = trim($value);
            yield $indent . sprintf('$this->setAttrValue(%s, %s, %s, false);', $elementVar, $this->toPhpLiteral($key), $expr);
            unset($attrs[$key]);
        }
    }

    private function codeText(Text $text, string $indent, string $parentVar, string $propsVar, string $mainVar, string $regionsVar): Generator
    {
        $data = $text->data;
        while ('' !== $data) {
            $hasExpr = 1 === preg_match('/{{\s*\S+\s*}}/', $data, $matches, \PREG_OFFSET_CAPTURE);
            if ($hasExpr) {
                $pos = (int) $matches[0][1];
                if ($pos > 0) {
                    $textData = $this->toPhpLiteral(substr($data, 0, $pos));
                    if ('' === $parentVar) {
                        yield $indent . sprintf('yield Text::𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Create(%s);', $textData);
                    } else {
                        yield $indent . sprintf('%s->childNodes->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Append(Text::𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Create(%s));', $parentVar, $textData);
                    }
                    $data = substr($data, $pos + 2);
                } else {
                    $data = substr($data, 2);
                }

                $pos = strpos($data, '}}');
                assert(false !== $pos);
                $expr = trim(substr($data, 0, $pos));
                if ('' === $parentVar) {
                    yield $indent . sprintf('yield $this->resolveInner(%s, %s, %s, %s);', $expr, $propsVar, $mainVar, $regionsVar);
                } else {
                    yield $indent . sprintf('$this->appendInner(%s, %s, %s, %s, %s);', $parentVar, $expr, $propsVar, $mainVar, $regionsVar);
                }

                $data = substr($data, $pos + 2);
            } else {
                if ('' === $parentVar) {
                    yield $indent . sprintf('yield Text::𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Create(%s);', $this->toPhpLiteral($data));
                } else {
                    yield $indent . sprintf('%s->childNodes->𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Append(Text::𝑖𝑛𝑡𝑒𝑟𝑛𝑎𝑙Create(%s));', $parentVar, $this->toPhpLiteral($data));
                }
                $data = '';
            }
        }
    }

    private function toPhpLiteral(string $value): string
    {
        $parts = explode("\n", $value);
        $parts = array_map(static fn (string $s): string => var_export($s, true), $parts);
        $literal = implode(' . "\n" . ', $parts);
        if (str_starts_with($literal, "'' . ")) {
            $literal = substr($literal, 5);
        }
        if (str_ends_with($literal, " . ''")) {
            $literal = substr($literal, 0, -5);
        }

        return $literal;
    }
}
