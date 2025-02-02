<?php

namespace Manychois\Phtml;

use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;

abstract class AbstractTemplate
{
    abstract public function content(HTMLDocument $doc, mixed $data): ?Node;
    abstract public function parent(): string;

    protected function parseHtmlFile(HTMLDocument $doc, string $filename, string $context = 'body'): Node
    {
        $raw = \file_get_contents($filename);
        $html = new Html($doc);
        return $html->parsePartial($raw, $context);
    }
}