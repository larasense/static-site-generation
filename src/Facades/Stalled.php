<?php

namespace Larasense\StaticSiteGeneration\Facades;
use Illuminate\Support\Facades\Facade;
use Larasense\StaticSiteGeneration\Services\StaticSiteService;

/**
 * @method \Illuminate\Http\Response|bool get(\Illuminate\Http\Request $request)
 * @method bool process(\Illuminate\Http\Request $request,\Illuminate\Http\Response|\Illuminate\Http\JsonResponse $response)
 * @method static array<int,array> all()
 * @method static array<int,string> urls()
 */
class Stalled extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return StaticSiteService::class;
    }

}
