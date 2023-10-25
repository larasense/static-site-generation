<?php

use Facades\Larasense\StaticSiteGeneration\Services\File;

it('should fail with message `No .env file.`', function () {
    File::shouldReceive('get')->andReturn(false);

    artisan('static:set-cache')
        ->expectsOutputToContain('No .env file.')
        ->assertNotExitCode(0)
    ;
    File::shouldNotHaveReceived('set');
});

it('should fail with message `No CACHE_DRIVER variable was found in the .env file`', function () {
    File::shouldReceive('get')->andReturn("some env content");

    artisan('static:set-cache')
        ->expectsOutputToContain('No CACHE_DRIVER variable was found in the .env file')
        ->assertNotExitCode(0)
    ;

    File::shouldNotHaveReceived('set');
});

it('should fail with message `SSG is disabled`', function () {
    Config::set('staticsitegen.enabled', false);
    File::spy();

    artisan('static:set-cache')
        ->expectsOutputToContain('SSG is disabled')
        ->assertNotExitCode(0)
    ;

    File::shouldNotHaveReceived('get');
    File::shouldNotHaveReceived('set');
});

it('should set the cache to redis', function () {
    registerRoutes();

    Config::set('cache.default', 'file');
    File::shouldReceive('get')->andReturn("CACHE_DRIVER=file")->once();
    File::shouldReceive('set')->once();

    artisan('static:set-cache')
        ->expectsOutputToContain('Cache set successfully.')
        ->assertExitCode(0)
    ;
});
