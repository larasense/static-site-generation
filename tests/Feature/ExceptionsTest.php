<?php
use Illuminate\Support\Facades\Config;
use Larasense\StaticSiteGeneration\Exceptions\BadCacheConfigException;
use Larasense\StaticSiteGeneration\Exceptions\StorageNotFoundException;
use Larasense\StaticSiteGeneration\Facades\Metadata;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Http\Response;

it('should throw StorageNotFoundException', function(){
    Config::set('filesystems.disks.ssg:store', null);

    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    $response = $middleware->handle($request, function () {});
})->throws(StorageNotFoundException::class);


it('should throw BadCacheConfigException', function(){

    Config::set('cache.driver', 'file');
    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    $response = $middleware->handle($request, function () {});
})->throws(BadCacheConfigException::class);




it('should call the $next function', function(){
    Metadata::shouldReceive('get')
         ->andReturn(false);

    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    //TODO: find a better way to mock a callback function
    /** @var bool */
    $next_was_called = false;
    $next = function() use(&$next_was_called) {
        $next_was_called = true;
        return ResponseFacade::make("", Response::HTTP_OK);
    };

    $response = $middleware->handle($request, $next);
    expect($next_was_called)->toBe(true);
});


