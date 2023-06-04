<?php

namespace Larasense\StaticSiteGeneration\DTOs;

use Larasense\StaticSiteGeneration\Attributes\SSG;
use ReflectionAttribute;

/**
 * Information related to the Page that could be statically generated
 *
 * @property bool $is_path_needed
 * @property bool $needed_revalidation
 */
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
        public ?int $revalidate = 0,
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
        if(isset($arguments['revalidate'])){
            $this->revalidate = $arguments['revalidate'];
        }
        return $this;
    }

    public function __get(string $name): mixed
    {
        switch ($name) {
            case 'is_path_needed':
                return str($this->uri)->contains("{") && !$this->path;  /** @phpstan-ignore-line */
            case 'needed_revalidation':
                if (!$this->file){
                    return false;
                }
                $timestamp = now()->timestamp;
                return ($this->file->timestamp + $this->revalidate * 1000) < $timestamp;
        }
        return null;
    }


}

