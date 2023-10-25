<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration\Attributes;

use Attribute;

#[Attribute]
class SSG
{
    /**
     *
     * @param array<string> $security
     */
    public function __construct(public ?string $paths = null, public ?int $revalidate = 0, public array $security=[])
    {
    }
}
