<?php

namespace Larasense\StaticSiteGeneration\DTOs;

use Larasense\StaticSiteGeneration\Attributes\SSG;
use ReflectionAttribute;

class Page
{
    /**
     *
     * @param string|array<int,string> $urls
     */
    public function __construct(
        public readonly string $uri,
        public readonly string $controller,
        public readonly string $method,
        public ?string $path = null,
        public string|array $urls = '',
        public ?FileInfo $file = null
    ){}

    /**
     * @param ReflectionAttribute<SSG> $attribute
     */
    public function setAttribute(ReflectionAttribute $attribute): self
    {
        $arguments = $attribute->getArguments();
        if(isset($arguments['path'])){
            $this->path = $arguments['path'];
        }
        return $this;
    }

    public function __get(string $name): mixed
    {
        switch ($name) {
            case 'is_path_needed':
                return str($this->uri)->contains("{") && !$this->path;  /** @phpstan-ignore-line */
        }
        return null;
    }


}

