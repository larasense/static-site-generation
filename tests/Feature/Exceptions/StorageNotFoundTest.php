<?php

use Illuminate\Support\Facades\Config;
use Larasense\StaticSiteGeneration\Exceptions\StorageNotFoundException;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Illuminate\Http\Request;

it('should not throw StorageNotFoundException', function(){

    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    $response = $middleware->handle($request, fn() => fakeResponse("StorageConfigFound"));
    expect($response->getContent())->toBe("StorageConfigFound");

});

it('should not throw StorageNotFoundException when disabled', function(){
    Config::set('filesystems.disks.ssg:store', null);
    Config::set('staticsitegen.enabled', false);

    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    $response = $middleware->handle($request, fn() => fakeResponse("StorageConfigFound"));
    expect($response->getContent())->toBe("StorageConfigFound");

});

it('should throw StorageNotFoundException', function(){
    Config::set('filesystems.disks.ssg:store', null);

    $request = Request::create('/', 'GET');
    $middleware = new SSGMiddleware;

    $response = $middleware->handle($request, function () {});
})->throws(StorageNotFoundException::class);

