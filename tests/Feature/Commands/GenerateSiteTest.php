<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

it('should get all ulrs and generate the files in html and json version', function () {
    registerRoutes();

    $http = Http::spy();
    $response = fakeResponse(['props'=>[]]);
    Http::shouldReceive('withHeaders')->with(['X-Inertia' => 'true'])->andReturn($http)->times(3);
    Http::shouldReceive('get')->with('http://localhost')->andReturn($response)->times(2);
    Http::shouldReceive('get')->with('http://localhost/show/1')->andReturn($response)->times(2);
    Http::shouldReceive('get')->with('http://localhost/show/2')->andReturn($response)->times(2);

    artisan('static:generate-site')->assertSuccessful();

});

it('should get all ulrs and generate the files in html version', function () {
    registerRoutes();
    Config::set('staticsitegen.inertia', false);
    Http::spy();
    $response = fakeResponse(['props'=>[]]);
    Http::shouldNotReceive('withHeaders')->with(['X-Inertia' => 'true']);
    Http::shouldReceive('get')->with('http://localhost')->andReturn($response)->times(1);
    Http::shouldReceive('get')->with('http://localhost/show/1')->andReturn($response)->times(1);
    Http::shouldReceive('get')->with('http://localhost/show/2')->andReturn($response)->times(1);

    artisan('static:generate-site')->assertSuccessful();

});

it('should not generate the files in html or json version When disabled', function () {
    $routes = registerRoutes();
    Config::set('staticsitegen.enabled', false);
    Http::spy();

    artisan('static:generate-site')
        ->assertSuccessful()
        ->expectsOutputToContain('SSG is disabled')
    ;

    Http::shouldNotHaveReceived('get');
});
