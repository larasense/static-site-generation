<?php

namespace Larasense\StaticSiteGeneration\Facades;

use Illuminate\Support\Facades\Facade;
use Larasense\StaticSiteGeneration\Services\MetadataService;

/**
 * Facade funtions that deal with the Attributes
 *
 *  @mixin \Larasense\StaticSiteGeneration\Services\MetadataService
 */
class Metadata extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return MetadataService::class;
    }

}
