<?php

use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(Larasense\StaticSiteGeneration\Tests\TestCase::class)
    ->beforeEach(function () {
        Config::set('filesystems.disks.ssg:store', ['driver'=>'local', 'root'=>'/tmp', 'throw' => false]);
    })
    ->group('features')
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/
use Larasense\StaticSiteGeneration\Tests\Stubs\Controllers\{TestPathController, TestRevalidateController};
use Illuminate\Support\Facades\Route;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Http\Response;

function artisan(string $command): \Illuminate\Testing\PendingCommand
{
    return test()->artisan($command); // phpstan-ignore-line
}

/**
 *
 * @param array<string, mixed>|string $content
 */
function fakeResponse(string|array $content = ""): Response
{
    return ResponseFacade::make($content);
}

/**
 *
 * @param array<string, mixed> $data
 */
function fakeView(array $data = []): Response
{
    return ResponseFacade::view('app', data: $data);
}
/**
 * @return array<int,\Illuminate\Routing\Route>
 */
function registerRoutes(): array
{
    $routes = [
         Route::get('/show/{id}', [TestPathController::class, 'show'])->middleware(SSGMiddleware::class),
         Route::get('/', [TestRevalidateController::class, 'index'])->middleware(SSGMiddleware::class),
    ];
    return $routes;
}
