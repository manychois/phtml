<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use Manychois\Peval\Evaluator;
use Manychois\Peval\Parser;
use Manychois\Simdom\Document;
use Manychois\Simdom\HtmlParser;
use RuntimeException;

class ViewEngine
{
    /**
     * @var array<string> an array of directories where view files are located
     */
    private array $directories = [];

    /**
     * Constructs a new ViewEngine instance with the specified directories.
     *
     * @param array<string> $directories an array of directories where view files are located
     */
    public function __construct(array $directories = [])
    {
        $this->directories = $directories;
    }

    /**
     * Renders a view with the provided data.
     *
     * @param string              $viewName the name of the view to render
     * @param array<string,mixed> $data     the data to pass to the view
     *
     * @return Document the rendered document containing the view's content
     */
    public function render(string $viewName, array $data): Document
    {
        $doc = Document::create();
        $view = $this->convertToView($viewName);
        $fragment = $view->render($data, null);
        $doc->appendChild($fragment);

        return $doc;
    }

    public function convertToView(string $name): View
    {
        $source = false;
        foreach ($this->directories as $dir) {
            $file = $dir . '/' . $name . '.html';
            if (is_file($file)) {
                $source = file_get_contents($file);
                break;
            }
        }
        if (false === $source) {
            throw new RuntimeException(sprintf('View file not found: %s', $name));
        }

        $htmlParser = new HtmlParser();
        $fragment = $htmlParser->parseFragment($source);

        return new View($name, $this, $fragment);
    }

    /**
     * Evaluates a PHP expression in the context of the provided data.
     *
     * @param string               $source  the PHP expression to evaluate
     * @param array<string, mixed> $context the context in which to evaluate the expression
     *
     * @return mixed the result of the evaluation
     */
    public function evaluateExpr(string $source, array $context): mixed
    {
        $parser = new Parser();
        $expr = $parser->parse($source);
        $evaluator = new Evaluator();

        return $evaluator->evaluate($expr, $context);
    }
}
