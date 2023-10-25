<?php

use Facades\Larasense\StaticSiteGeneration\Services\File;

it('should not disable cache when it is already disabled', function () {
    registerRoutes();

    File::spy();
    Config::set('staticsitegen.cached', false);

    artisan('static:disable-cache')
        ->expectsOutputToContain('SSG Cache is Already disabled.')
        ->assertExitCode(0)
    ;
    File::shouldNotHaveReceived('get');
    File::shouldNotHaveReceived('set');
});

it('should disable cache', function () {
    registerRoutes();

    Config::set('staticsitegen.cached', true);
    File::shouldReceive('get')->andReturn("SSG_CACHE_ENABLED=true")->once();
    File::shouldReceive('set')->once();

    artisan('static:disable-cache')
        ->expectsOutputToContain('Cache disabled successfully.')
        ->assertExitCode(0)
    ;
});

it('should disable cache even if SSG is disabled', function () {
    registerRoutes();

    Config::set('staticsitegen.enabled', false);
    Config::set('staticsitegen.cached', true);
    File::shouldReceive('get')->andReturn("SSG_CACHE_ENABLED=true")->once();
    File::shouldReceive('set')->once();

    artisan('static:disable-cache')
        ->expectsOutputToContain('Cache disabled successfully.')
        ->assertExitCode(0)
    ;
});

it('should disable cache even when the SSG_CACHE_ENABLED is not set in the .env', function () {
    registerRoutes();

    Config::set('staticsitegen.cached', true);
    File::shouldReceive('get')->andReturn("SOME=env")->once();
    File::shouldReceive('set')->once();

    artisan('static:disable-cache')
        ->expectsOutputToContain('Cache disabled successfully.')
        ->assertExitCode(0)
    ;
});
