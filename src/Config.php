<?php

namespace Manychois\Phtml;

use Dom\DocumentFragment;
use Dom\Element;
use Dom\Node;

class Config
{
    public string $templateDir = '';
    private $transformers = [];
    public function registerTransformer(string $querySelector, TransformerInterface $e)
    {
        $this->transformers[$querySelector] = $e;
    }

    /**
     * @return array<string, TransformerInterface>
     */
    public function getTransformers(): array
    {
        return $this->transformers;
    }
}
