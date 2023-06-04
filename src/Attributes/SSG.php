<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration\Attributes;

use Attribute;

#[Attribute]
class SSG
{
    public function __construct(public ?string $paths = null, public ?int $revalidate = 0) {}
}
