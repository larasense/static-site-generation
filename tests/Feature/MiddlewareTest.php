<?php
use Larasense\StaticSiteGeneration\Facades\StaticSite;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Illuminate\Http\Request;


it('should call the $next function', function(){

    $routes = registerRoutes();

    $request = Request::create('/', 'GET');
    $request->setRouteResolver(fn()=>$routes[1]);
    $middleware = new SSGMiddleware;

    //TODO: find a better way to mock a callback function
    /** @var bool */
    $next_was_called = false;
    $next = function() use(&$next_was_called) {
        $next_was_called = true;
        return fakeResponse();
    };

    $response = $middleware->handle($request, $next);
    expect($next_was_called)->toBe(true);
});

it('should not call the $next function and retrieve a file from disk', function(){

    $routes = registerRoutes();
    StaticSite::shouldReceive('getContent')->andReturn('Content From Disk');
    StaticSite::makePartial();

    $request = Request::create('/', 'GET');
    $request->setRouteResolver(fn()=>$routes[1]);
    $middleware = new SSGMiddleware;

    //TODO: find a better way to mock a callback function
    /** @var bool */
    $next_was_called = false;
    $next = function() use(&$next_was_called) {
        $next_was_called = true;
        return fakeResponse();
    };

    $response = $middleware->handle($request, $next);
    expect($next_was_called)->toBe(false);

    expect($response->getContent())->toBe("Content From Disk");

});

it('should call the $next function and  not retrieve a file from disk when disabled', function(){

    Config::set('staticsitegen.enabled', false);
    $routes = registerRoutes();
    StaticSite::shouldReceive('getContent')->andReturn('Content From Disk');
    StaticSite::makePartial();

    $request = Request::create('/', 'GET');
    $request->setRouteResolver(fn()=>$routes[1]);
    $middleware = new SSGMiddleware;

    //TODO: find a better way to mock a callback function
    /** @var bool */
    $next_was_called = false;
    $next = function() use(&$next_was_called) {
        $next_was_called = true;
        return fakeResponse("Content From Controller");
    };

    $response = $middleware->handle($request, $next);
    expect($next_was_called)->toBe(true);

    expect($response->getContent())->toBe("Content From Controller");

});
