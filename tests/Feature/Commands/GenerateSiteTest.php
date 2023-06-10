<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('should get all ulrs and generate the files in html and json version', function() {
    registerRoutes();

    $http = Http::spy();
    Http::shouldReceive('withHeaders')->with(['X-Inertia' => 'true'])->andReturn($http)->times(3);

    artisan('static:generate-site')->assertSuccessful();


    Http::shouldHaveReceived('get')->with('http://localhost')->times(2);
    Http::shouldHaveReceived('get')->with('http://localhost/show/1')->times(2);
    Http::shouldHaveReceived('get')->with('http://localhost/show/2')->times(2);
});

it('should get all ulrs and generate the files in html version', function() {
    registerRoutes();
    Config::set('staticsitegen.inertia', false);
    Http::spy();
    Http::shouldNotReceive('withHeaders')->with(['X-Inertia' => 'true']);

    artisan('static:generate-site')->assertSuccessful();

    Http::shouldHaveReceived('get')->with('http://localhost')->times(1);
    Http::shouldHaveReceived('get')->with('http://localhost/show/1')->times(1);
    Http::shouldHaveReceived('get')->with('http://localhost/show/2')->times(1);
});

it('should not generate the files in html or json version When disabled', function() {
    registerRoutes();
    Config::set('staticsitegen.enabled', false);
    Http::spy();

    artisan('static:generate-site')
        ->assertSuccessful()
        ->expectsOutputToContain('SSG is disabled')
        ;

    Http::shouldNotHaveReceived('get');
});
