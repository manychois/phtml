<?php

declare(strict_types=1);

namespace Manychois\Phtml;

use Psr\Container\ContainerInterface;

class Config
{
    public readonly string $baseDir;
    public readonly string $compiledDir;
    public readonly bool $isDev;
    public readonly ?ContainerInterface $container;
    public readonly string $baseClass;

    public function __construct(
        string $baseDir,
        string $compiledDir,
        ?ContainerInterface $container = null,
        bool $isDev = true,
        string $baseClass = AbstractView::class,
    ) {
        $this->baseDir = $baseDir;
        $this->compiledDir = $compiledDir;
        $this->isDev = $isDev;
        $this->container = $container;
        $this->baseClass = $baseClass;
    }
}
