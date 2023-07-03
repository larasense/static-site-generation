<?php declare(strict_types = 1);
namespace Larasense\StaticSiteGeneration\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Larasense\StaticSiteGeneration\DTOs\Page;
use Larasense\StaticSiteGeneration\DTOs\FileInfo;
use Larasense\StaticSiteGeneration\Exceptions\StorageNotFoundException;
use Larasense\StaticSiteGeneration\Facades\Metadata;
use Larasense\StaticSiteGeneration\Exceptions\BadCacheConfigException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Larasense\StaticSiteGeneration\Jobs\ProcessStaticContent;

class StaticSiteService
{
    public function checkEnvironment(Request $request):bool
    {
        return $this->enabled() &&
               $this->isRequestOk($request) &&
               $this->isCacheOk() &&
               $this->isStorageOk();
    }

    public function get(Request $request): false | Response
    {
        if (!$this->checkEnvironment($request)){
            return false;
        }

        if (!$metadata = Metadata::get($request->route())) {
            return false;
        }

        $metadata->file = $this->getFileInfo($request);

        if ($metadata->need_revalidation){
            return false;
        }

        $content = $this->getContent($metadata->file);

        if (!$content) {
            return false;
        }

        $response = ResponseFacade::make($content, Response::HTTP_OK);
        $response->header('Content-Type', $metadata->file->extention === 'html' ? 'text/html' : 'application/json');
        $response->header('X-SSG', 'true');
        if ($metadata->file->extention == 'json'){
            $response->header('X-Inertia', 'true');
        }
        return $response;
    }

    /**
     *
     * @return array<int,Page>
     */
    public function all(): array
    {
        /** @phpstan-ignore-next-line */
        return Metadata::all()
            ->map(function(Page $metadata){
                if(isset($metadata->path)){
                    $class = $metadata->controller;
                    $path = $metadata->path;
                    $arguments = $class::$path();
                    $paths = [];
                    foreach ($arguments as $argument){
                        $paths[] = action([$metadata->controller,$metadata->method], $argument);
                    }
                    $metadata->urls = $paths;
                } else {
                    $metadata->urls = action([$metadata->controller,$metadata->method]);
                }
                return $metadata;
        })->toArray();
    }

    /**
     *
     * @return array<int, string>
     */
    public function urls(): array
    {
        /** @phpstan-ignore-next-line */
        return collect($this->all())->pluck('urls')->flatten()->toArray();
    }

    public function process(Request $request, Response | JsonResponse $response): bool
    {
        $content = $response->getContent();
        if($content){
            $file = $this->getFileInfo($request);
            ProcessStaticContent::dispatch($content, $file->filename);
            return false;
        }
        return true;
    }

        /**
         *
         * @return array<string,mixed>
         */
    public function getUserInfo(): array
    {
            $user = auth()->user();
            return [
                'user' => [
                    'userInfo' => route('staticsitegen:current'),
                    'updated_at' => $user->updated_at ?? null
                ]
            ];
    }


    protected function getFileInfo(Request $request): FileInfo
    {
        $extention = $request->header('X-Inertia') !== 'true' ? 'html' : 'json';
        $pathParts = explode('/', trim($request->getPathInfo(), '/'));
        $filePart = array_pop($pathParts);

        return new FileInfo(
            filename: implode("/", $pathParts) . "/" . (strlen($filePart) ? $filePart : "index") . '.' . $extention,
            extention: $extention,
        );
    }

    protected function getContent(FileInfo $file_info): string|bool|null
    {
        return config('staticsitegen.cache_enabled')
            ? $this->getCachedFileContent($file_info)
            : $this->getFileContent($file_info);
    }

    protected function getCachedFileContent(FileInfo $file_info): string|bool|null
    {
        /** @var int */
        $seconds = config('staticsitegen.remember');
        return Cache::remember("ssg:{$file_info->filename}", $seconds, function() use($file_info){
            return $this->getFileContent($file_info);
        });
    }

    protected function getFileContent(FileInfo $file_info): string|bool|null
    {
            /** @var string */
            $disk = config('staticsitegen.storage_name');

            if (!Storage::disk($disk)->exists($file_info->filename)) {
                return false;
            }
            return Storage::disk($disk)->get($file_info->filename);
    }

    protected function isRequestOk(Request $request): bool
    {
        if ($request->getMethod() !== 'GET' || !is_null($request->header('sgg-no-cache'))) {
            return false;
        }
        return true;
    }

    protected function isCacheOk(): bool
    {
        return config('cache.driver') === 'file' ? throw_if(!app()->environment('production'), BadCacheConfigException::class) : true; /** @phpstan-ignore-line */
    }

    protected function isStorageOk(): bool
    {
        $storage_name = config('staticsitegen.storage_name');
        return null === config("filesystems.disks.$storage_name") ? throw_if(!app()->environment('production'), StorageNotFoundException::class, $storage_name): true; /** @phpstan-ignore-line */

    }

    public function enabled(): bool
    {
        return config('staticsitegen.enabled')?true:false;
    }
    public function cached(): bool
    {
        return config('staticsitegen.cached')?true:false;
    }
    public function withInertia(): bool
    {
        return config('staticsitegen.inertia')?true:false;
    }

}
