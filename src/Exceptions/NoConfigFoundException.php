<?php

namespace Larasense\StaticSiteGeneration\Exceptions;

use Exception;
use Spatie\Ignition\Contracts\RunnableSolution;
use Larasense\StaticSiteGeneration\Solutions\RunVendorPublishSolution;
use Spatie\Ignition\Contracts\ProvidesSolution;

class NoConfigFoundException extends Exception implements ProvidesSolution
{
    public function __construct()
    {
        parent::__construct(code: 444, message: "No Configuration Found for SSG package");
    }
    public function getSolution(): RunnableSolution
    {
        return new RunVendorPublishSolution();
    }
}

