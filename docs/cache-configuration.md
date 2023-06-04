# Cache Configuration

Performance is one of the main reason for using this package.

By bypassing all the controller execution, ssr generation and blade interpretation, by it self, it already make a significant improvement.

But, it still store all html and json files in a disk, wheather it is S3 or in the instance.

if a redis server can be set on edge with the instance, that could be another big inprovement.

### Define cache revalidation.

```bash
// .env file

SSG_CACHE_REMEMBER=3600 // seconds (1 hr)

```

## Bad Cache configuration

As mentioned before all files are stored in a disk (S3, file, or similar). If the laravel application is set to use a file cache, that will add no real improvement in the application Performance.

for that reason, the recomended cache driver configuration in `cache.driver` is redis.

```bash
// .env file

CACHE_DRIVER=redis

```

if for some reason, using redis or a similar database can't be used, it is better to disable cache on the `static-site-generation` config

```bash
// .env file

SSG_CACHE_ENABLED=false

```
