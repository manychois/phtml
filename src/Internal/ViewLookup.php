<?php

declare(strict_types=1);

namespace Manychois\Phtml\Internal;

class ViewLookup
{
    /**
     * @param array<string,array<string,array<string,string>>> $properties
     */
    public static function __set_state(array $properties): self
    {
        $obj = new self();
        $obj->lookup = $properties['lookup'] ?? [];

        return $obj;
    }

    /**
     * @var array<string,array<string,string>>
     */
    private array $lookup = [];

    public function set(string $viewName, string $compiled, string $class, string $sourcePath, string $hash): void
    {
        $this->lookup[$viewName] = [
            'compiled' => $compiled,
            'class' => $class,
            'source' => $sourcePath,
            'hash' => $hash,
        ];
    }

    /**
     * @return array<string,string>
     */
    public function get(string $viewName): array
    {
        return $this->lookup[$viewName] ?? [];
    }

    public function export(string $filePath): void
    {
        file_put_contents($filePath, '<?php return ' . var_export($this, true) . ';');
    }
}
