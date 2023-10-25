<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration\Solutions;

use Illuminate\Support\Facades\Artisan;
use Spatie\Ignition\Contracts\RunnableSolution;

class RunVendorPublishSolution implements RunnableSolution
{
    public function getSolutionTitle(): string
    {
        return 'Looks like you did not run vendor:publish the config for SSG attribute to work';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Read more' => 'https://github.com/larasense/static-site-generation/blob/main/README.md',
        ];
    }

    public function getSolutionActionDescription(): string
    {
        return '`php artisan vendor:publish --tag=staticsitegen-config`.';
    }

    public function getRunButtonText(): string
    {
        return 'Publish config';
    }

    public function getSolutionDescription(): string
    {
        return 'We need to set some things up before static files can be generated';
    }

    public function run(array $parameters = []): void
    {
        Artisan::call('vendor:publish', ['--tag'=>'staticsitegen-config']);
    }

    public function getRunParameters(): array
    {
        return [];
    }
}
