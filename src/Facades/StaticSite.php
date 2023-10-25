<?php

namespace Larasense\StaticSiteGeneration\Facades;

use Illuminate\Support\Facades\Facade;
use Larasense\StaticSiteGeneration\Services\StaticSiteService;

/**
 * @mixin StaticSiteService;
 */
class StaticSite extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return StaticSiteService::class;
    }

}
