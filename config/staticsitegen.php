<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Static Site generation
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */

    'enabled' => env('SSG_ENABLED', false),
    'cached' => env('SSG_CACHE_ENABLED', false),
    'remember' => env('SSG_CACHE_REMEMBER', 60*60), // 1HR

    'storage_name' => env('SSG_STORAGE_NAME', 'static_generated_files'),

];

