<?php

namespace Larasense\StaticSiteGeneration\Solutions\Providers;

use Throwable;
use Larasense\StaticSiteGeneration\Exceptions\NoConfigFoundException;
use Larasense\StaticSiteGeneration\Solutions\RunVendorPublishSolution;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;

class StaticSiteGenerationSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        return ($throwable instanceof NoConfigFoundException);
    }

    public function getSolutions(Throwable $throwable): array
    {
        return [
            new RunVendorPublishSolution(),
        ];
    }
}
