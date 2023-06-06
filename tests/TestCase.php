<?php

namespace Larasense\StaticSiteGeneration\Tests;


use Orchestra\Testbench\TestCase as Orchestra;
use Larasense\StaticSiteGeneration\StaticSiteGenerationServiceProvider;
use Spatie\LaravelIgnition\IgnitionServiceProvider;


class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            IgnitionServiceProvider::class,
            StaticSiteGenerationServiceProvider::class,
        ];
    }
}
