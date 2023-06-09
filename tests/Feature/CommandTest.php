<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Larasense\StaticSiteGeneration\Facades\StaticSite;
use Larasense\StaticSiteGeneration\Tests\Stubs\Controllers\{TestPathController, TestRevalidateController};
use Facades\Larasense\StaticSiteGeneration\Services\File;


it('should get all ulrs and generate the files in html and json version', function() {
    StaticSite::shouldReceive('urls')
        ->andReturns(["url1", "url2"]);
    $http = Http::spy();
    Http::shouldReceive('withHeaders')->with(['X-Inertia' => 'true'])->andReturn($http)->times(2);

    $this->artisan('static:generate-site')->assertSuccessful();


    Http::shouldHaveReceived('get')->with('url1')->times(2);
    Http::shouldHaveReceived('get')->with('url2')->times(2);
});


it('list all routes with details of the SSG data', function(){
    registerRoutes();

    artisan('static:list')
        ->assertSuccessful()
        ->expectsOutputToContain('Controller: '.TestRevalidateController::class)
        ->expectsOutputToContain('Method: index')
        ->expectsOutputToContain("Uri: /")
        ->expectsOutputToContain('Controller: '. TestPathController::class)
        ->expectsOutputToContain('Method: show')
        ->expectsOutputToContain('Uri: show/{id}')
        ->expectsTable(['Urls'],[
            ['http://localhost/show/1'],
            ['http://localhost/show/2'],
        ])
        ->expectsTable(['Urls'],[
            ['http://localhost']
        ])
    ;
});

it('should fail with message `No .env file.`', function(){
    File::shouldReceive('get')->andReturn(null);

    artisan('static:set-cache')
        ->expectsOutputToContain('No .env file.')
        ->assertNotExitCode(0)
    ;
});

it('should fail with message `No CACHE_DRIVER variable was found in the .env file`', function(){
    File::shouldReceive('get')->andReturn("some env content");

    artisan('static:set-cache')
        ->expectsOutputToContain('No CACHE_DRIVER variable was found in the .env file')
        ->assertNotExitCode(0)
    ;

});

it('should set the cache to redis', function(){
    registerRoutes();

    Config::set('cache.default', 'file');
    File::shouldReceive('get')->andReturn("CACHE_DRIVER=file")->once();
    File::shouldReceive('set')->once();

    artisan('static:set-cache')
        ->expectsOutputToContain('Cache set successfully.')
        ->assertExitCode(0)
    ;
});


it('should disable cache', function(){
    registerRoutes();

    Config::set('staticsitegen.cached', true);
    File::shouldReceive('get')->andReturn("SSG_CACHE_ENABLED=true")->once();
    File::shouldReceive('set')->once();

    artisan('static:disable-cache')
        ->expectsOutputToContain('Cache set successfully.')
        ->assertExitCode(0)
    ;
});
