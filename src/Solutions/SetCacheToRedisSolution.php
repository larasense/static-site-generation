<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration\Solutions;

use Illuminate\Support\Facades\Artisan;
use Spatie\Ignition\Contracts\RunnableSolution;

class SetCacheToRedisSolution implements RunnableSolution
{
    public function getSolutionTitle(): string
    {
        return 'You may want to change the cache driver to `redis`';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Laravel' => 'https://laravel.com/docs/master/installation#configuration',
        ];
    }

    public function getSolutionActionDescription(): string
    {
        return '`php artisan vendor:publish --tag=staticsitegen-config`.';
    }

    public function getRunButtonText(): string
    {
        return 'Set Cache to REDIS (recomended)';
    }

    public function getSolutionDescription(): string
    {
        return 'There is no difference with the storage static files';
    }

    public function run(array $parameters = []):void
    {
        Artisan::call('static:set-cache');
    }

    public function getRunParameters(): array
    {
        return [];
    }
}

