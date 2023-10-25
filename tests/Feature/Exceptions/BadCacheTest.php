<?php

use Larasense\StaticSiteGeneration\Exceptions\BadCacheConfigException;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

it('should not throw BadCacheConfigException', function () {

    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware();

    $response = $middleware->handle($request, fn () => fakeResponse("GoodCacheConfig"));
    expect($response->getContent())->toBe("GoodCacheConfig");

});

it('should not throw BadCacheConfigException when disabled', function () {

    Config::set('cache.driver', 'file');
    Config::set('staticsitegen.enabled', false);
    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware();

    $response = $middleware->handle($request, fn () => fakeResponse("GoodCacheConfig"));
    expect($response->getContent())->toBe("GoodCacheConfig");

});

it('should throw BadCacheConfigException', function () {

    $routes = registerRoutes();
    Config::set('cache.driver', 'file');
    Config::set('staticsitegen.dev_enabled', true);
    $request = Request::create('/', 'GET');
    $request->setRouteResolver(fn () =>$routes[1]);
    $middleware = new SSGMiddleware();

    $response = $middleware->handle($request, fn () => fakeResponse("BadCacheConfig"));
})->throws(BadCacheConfigException::class);
