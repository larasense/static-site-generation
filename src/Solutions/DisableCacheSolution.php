<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration\Solutions;

use Illuminate\Support\Facades\Artisan;
use Spatie\Ignition\Contracts\RunnableSolution;

class DisableCacheSolution implements RunnableSolution
{
    public function getSolutionTitle(): string
    {
        return 'Other Option is to not use the cache for SSG';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Read more' => 'https://github.com/larasense/static-site-generation/blob/main/docs/cache-configuration.md#bad-cache-configuration',
        ];
    }

    public function getSolutionActionDescription(): string
    {
        return '`php artisan vendor:publish --tag=staticsitegen-config`.';
    }

    public function getRunButtonText(): string
    {
        return 'Disable SSG Cache layer';
    }

    public function getSolutionDescription(): string
    {
        return 'There is no difference with the storage static files';
    }

    public function run(array $parameters = []): void
    {
        Artisan::call('static:disable-cache');
    }

    public function getRunParameters(): array
    {
        return [];
    }
}
