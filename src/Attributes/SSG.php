<?php

namespace Larasense\StaticSiteGeneration\Attributes;

use Attribute;

#[Attribute]
class SSG
{
    public function __construct(public ?string $paths = null) {}
}
