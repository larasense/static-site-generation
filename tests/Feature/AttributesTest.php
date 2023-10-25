<?php

use Illuminate\Support\Facades\Route;
use Larasense\StaticSiteGeneration\Facades\Metadata;
use Larasense\StaticSiteGeneration\Facades\StaticSite;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Larasense\StaticSiteGeneration\Tests\Stubs\Controllers\{TestPathController, TestRevalidateController};

it('should run the path function', function () {
    $route = Route::get('/show/{id}', [TestPathController::class, 'show'])->middleware(SSGMiddleware::class);

    expect(StaticSite::urls())
        ->toBeArray()
        ->toContain('http://localhost/show/1', 'http://localhost/show/2')
    ->and(Metadata::get($route))
        ->path->toBe('getStaticPath')
        ->is_path_needed->toBe(false)
    ;
});

it('should read the revalidate value', function () {
    $route = Route::get('/', [TestRevalidateController::class, 'index'])->middleware(SSGMiddleware::class);

    expect(Metadata::get($route))
        ->revalidate->toBe(60)
        ->need_revalidation->toBe(false)
    ;
});
