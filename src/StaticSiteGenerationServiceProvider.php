<?php

declare(strict_types=1);

namespace Larasense\StaticSiteGeneration;

use Illuminate\Support\ServiceProvider;
use Larasense\StaticSiteGeneration\Console\Commands\GenerateStaticSiteCommand;
use Larasense\StaticSiteGeneration\Console\Commands\ListPagesCommand;
use Larasense\StaticSiteGeneration\Console\Commands\SetCacheDriverToRedisCommand;
use Larasense\StaticSiteGeneration\Console\Commands\DisableCacheCommand;
use Larasense\StaticSiteGeneration\Facades\Metadata;
use Larasense\StaticSiteGeneration\Http\Middleware\SSGMiddleware;
use Illuminate\Routing\Route;
use Larasense\StaticSiteGeneration\Solutions\Providers\StaticSiteGenerationSolutionProvider;
use Spatie\Ignition\Contracts\SolutionProviderRepository;

final class StaticSiteGenerationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    GenerateStaticSiteCommand::class,
                    SetCacheDriverToRedisCommand::class,
                    DisableCacheCommand::class,
                    ListPagesCommand::class,
                ],
            );
        }
        $this->publishes([
            __DIR__.'/../config/staticsitegen.php' => config_path('staticsitegen.php')
        ], 'staticsitegen-config');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->app->make(SolutionProviderRepository::class)->registerSolutionProvider(StaticSiteGenerationSolutionProvider::class);

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
