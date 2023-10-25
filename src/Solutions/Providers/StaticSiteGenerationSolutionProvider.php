<?php

namespace Larasense\StaticSiteGeneration\Solutions\Providers;

use Throwable;
use Larasense\StaticSiteGeneration\Exceptions\SSGException;
use Larasense\StaticSiteGeneration\Exceptions\BadCacheConfigException;
use Larasense\StaticSiteGeneration\Solutions\{SetCacheToRedisSolution, DisableCacheSolution};
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class StaticSiteGenerationSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        return ($throwable instanceof SSGException);
    }

    public function getSolutions(Throwable $throwable): array
    {
        if ($throwable instanceof BadCacheConfigException) {
            return [
                new SetCacheToRedisSolution(),
                new DisableCacheSolution()
            ];
        }

        return [];
    }
}
