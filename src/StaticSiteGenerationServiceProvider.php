<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration;

use Illuminate\Support\ServiceProvider;
use Larasense\StaticSiteGeneration\Console\Commands\GenerateStaticSite;
use Larasense\StaticSiteGeneration\Console\Commands\SetCacheDriverToRedisCommand;
use Larasense\StaticSiteGeneration\Facades\Metadata;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Illuminate\Routing\Route;

final class StaticSiteGenerationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    GenerateStaticSite::class,
                    SetCacheDriverToRedisCommand::class
                ],
            );
        }
        $this->publishes([
            __DIR__.'/../config/staticsitegen.php' => config_path('staticsitegen.php')
        ], 'staticsitegen-config');

        $this->loadAll();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/staticsitegen.php', 'staticsitegen'
        );
    }

    protected function loadAll():void
    {
        if (!$this->app->runningInConsole()) {
            Metadata::routes()->each(function(Route $route) {
                $route->middleware(SSGMiddleware::class);
            });
        }

    }

}
