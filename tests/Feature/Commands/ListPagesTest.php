<?php

use Illuminate\Support\Facades\Config;
use Larasense\StaticSiteGeneration\Tests\Stubs\Controllers\{TestPathController, TestRevalidateController};

it('list all routes with details of the SSG data', function () {
    registerRoutes();

    artisan('static:list')
        ->assertSuccessful()
        ->expectsOutputToContain('Controller: '.TestRevalidateController::class)
        ->expectsOutputToContain('Method: index')
        ->expectsOutputToContain("Uri: /")
        ->expectsOutputToContain('Controller: '. TestPathController::class)
        ->expectsOutputToContain('Method: show')
        ->expectsOutputToContain('Uri: show/{id}')
        ->expectsTable(['Urls'], [
            ['http://localhost/show/1'],
            ['http://localhost/show/2'],
        ])
        ->expectsTable(['Urls'], [
            ['http://localhost']
        ])
    ;
});

it('should inform that SSG is disabled When disabled', function () {
    registerRoutes();
    Config::set('staticsitegen.enabled', false);

    artisan('static:list')
        ->expectsOutputToContain('SSG is disabled')
        ->assertSuccessful()
    ;
});
