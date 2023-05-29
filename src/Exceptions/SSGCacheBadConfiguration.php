<?php

namespace Larasense\StaticSiteGeneration\Exceptions;

use Exception;
use Spatie\Ignition\Contracts\Solution;
use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\ProvidesSolution;

class SSGCacheBadConfiguration extends Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        return BaseSolution::create('The cache driver is `file`')
            ->setSolutionDescription("Given that the HTML and JSON files are also stored in a file, this settings makes no sense. Use Redis or something similar in order to use this feature to improve performance in any way or it may be better to disable cache for this functionallity ")
            ->setDocumentationLinks([
                'Learn More here' => 'https://flareapp.io/docs',
            ]);
    }
}
