# Storage Disk configuration

you need to define a disk in the filesystem configuration in witch the files will be stored.

```php
// filesystem.php

'disks' => [
     //...
    'ssg:store' => [
        'driver' => 'local',
        'root' => storage_path('app/ssg'),
        'throw' => false,
    ],
];
```

## Default configuration

```bash
// .env file

SSG_STORAGE_NAME=ssg:store
```

## Recomended configuration

For production in a serverless setup or in a multiple instance deploy you may want to use S3 or something similar as a storage.

```php
// filesystem.php

'disks' => [
     //...
    'ssg:store' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
        'endpoint' => env('AWS_ENDPOINT'),
        'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        'throw' => false,
    ],
];

```
