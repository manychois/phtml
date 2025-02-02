<?php

namespace Manychois\Phtml;

use Dom\DocumentFragment;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;

interface TransformerInterface
{
    public function transform(HTMLDocument $doc, Element $element): ?Node;
}
