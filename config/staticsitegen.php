<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Static Site generation
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function
    |
    */
    'enabled' => env('SSG_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default DEV enabled
    |--------------------------------------------------------------------------
    |
    | This option controls the default behavior
    |
    */
    'dev_enabled' => env('SSG_DEV_ENABLED', false),
    'inertia' => env('SSG_INERTIA_ENABLED', true),
    'cached' => env('SSG_CACHE_ENABLED', true),
    'remember' => env('SSG_CACHE_REMEMBER', 60*60), // 1HR

    'storage_name' => env('SSG_STORAGE_NAME', 'ssg:store'),

];
