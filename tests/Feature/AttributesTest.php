<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Larasense\StaticSiteGeneration\Attributes\SSG;
use Larasense\StaticSiteGeneration\Facades\StaticSite;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;




it('should list the urls defined by attributes', function(){
    class AttributesTest extends Controller // php-stan-ignore
    {
        #[SSG(path: 'getStaticPath')]
        public function show(string $id): string
        {
            return $id;
        }

        /** @return array<array<string,int>> */
        public static function getStaticPath():array
        {
            return [
                [ 'id' => 1],
                [ 'id' => 2],
            ];
        }
    }
    Route::get('/show/{id}', [AttributesTest::class, 'show'])->middleware(SSGMiddleware::class);

    expect(StaticSite::urls())
    ->toBeArray()
    ->toContain('http://localhost/show/1', 'http://localhost/show/2')
    ;
});

it('should read the attributes from a controller class');

