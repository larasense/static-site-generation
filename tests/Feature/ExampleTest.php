<?php

use Illuminate\Support\Facades\Config;
use Larasense\StaticSiteGeneration\Exceptions\BadCacheConfigException;
use Larasense\StaticSiteGeneration\Exceptions\StorageNotFoundException;
use Larasense\StaticSiteGeneration\Facades\Metadata;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Http\Response;

it('will throw StorageNotFoundException', function(){

    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    $response = $middleware->handle($request, function () {});
})->throws(StorageNotFoundException::class);


it('will throw BadCacheConfigException', function(){

    Config::set('cache.driver', 'file');
    Config::set('filesystems.disks.ssg:store', ['driver'=>'file', 'root'=>'/tmp', 'throw' => false]);
    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    $response = $middleware->handle($request, function () {});
})->throws(BadCacheConfigException::class);




it('will call the $next function', function(){
    Config::set('cache.driver', 'redis');
    Config::set('filesystems.disks.ssg:store', ['driver'=>'file', 'root'=>'/tmp', 'throw' => false]);
    Metadata::shouldReceive('get')
         ->andReturn(false);

    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    //TODO: find a better way to mock a callback function
    $next_was_called = false;
    dump($next_was_called);
    $next = function() use(&$next_was_called) {
        $next_was_called = true;
        dump($next_was_called);
        return ResponseFacade::make("", Response::HTTP_OK);
    };

    $response = $middleware->handle($request, $next);
    dump($next_was_called);
    expect($next_was_called)->toBe(true);
});


