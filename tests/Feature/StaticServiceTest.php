<?php

use Larasense\StaticSiteGeneration\Facades\{StaticSite,Metadata};
use Illuminate\Support\Facades\Storage;
use Larasense\StaticSiteGeneration\DTOs\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

it('should need_revalidation and return false', function () {

    $one_second_to_one_hour = 60*60 - 1;

    Config::set('staticsitegen.dev_enabled', true);
    $routes = registerRoutes();
    $request = Request::create('/', 'GET');
    $request->setRouteResolver(fn () =>$routes[1]);

    Metadata::shouldReceive('get')->andReturn(new Page(
            uri:        "/",
            controller: "TestController",
            method:     "index",
            revalidate: $one_second_to_one_hour
    ));
    Metadata::makePartial();
    Storage::shouldReceive('disk')->andReturn(new class{
        public function exists(){
            return true;
        }
        public function lastModified(){
            return now()->add(-1, 'hour')->timestamp;
        }
    });


    $response = StaticSite::get($request);

    expect($response)->toBe(false);


});

it('should not need_revalidation and return the file', function () {

    $one_hour_and_one_second = 60*60 + 1;

    Config::set('staticsitegen.dev_enabled', true);
    $routes = registerRoutes();
    $request = Request::create('/', 'GET');
    $request->setRouteResolver(fn () =>$routes[1]);

    Metadata::shouldReceive('get')->andReturn(new Page(
            uri:        "/",
            controller: "TestController",
            method:     "index",
            revalidate: $one_hour_and_one_second
    ));
    Metadata::makePartial();
    Storage::shouldReceive('disk')->andReturn(new class{
        public function exists(){
            return true;
        }
        public function lastModified(){
            return now()->add(-1, 'hour')->timestamp;
        }
        public function get() {
            return "content";
        }
    });


    $response = StaticSite::get($request);

    expect($response)->toBeInstanceOf(\Illuminate\Http\Response::class);


});
