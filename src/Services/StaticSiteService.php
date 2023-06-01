<?php declare(strict_types = 1);
namespace Larasense\StaticSiteGeneration\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
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
        return $this->isRequestOk($request) &&
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

        [$filename, $extention] = $this->getFileInfo($request);
        $content = $this->getContent($filename, $extention);

        if (!$content) {
            return false;
        }

        $response = ResponseFacade::make($content, Response::HTTP_OK);
        $response->header('Content-Type', $extention === 'html' ? 'text/html' : 'application/json');
        $response->header('X-SSG', 'true');
        if ($extention == 'json'){
            $response->header('X-Inertia', 'true');
        }
        return $response;

    }

    /**
     *
     * @return array{array{'uri':string,'path':?string,'controller':string,'method':string,'urls':string|array<int,string>}}
     */
    public function all(): array
    {
        /** @phpstan-ignore-next-line */
        return Metadata::all()
            ->map(function($metadata){
                if(isset($metadata['path'])){
                    $class = $metadata['controller'];
                    $path = $metadata['path'];
                    $arguments = $class::$path();
                    $paths = [];
                    foreach ($arguments as $argument){
                        $paths[] = action([$metadata['controller'],$metadata['method']], $argument);
                    }
                    $metadata['urls'] = $paths;
                } else {
                    $metadata['urls'] = action([$metadata['controller'],$metadata['method']]);
                }
                return $metadata;
        })->toArray();
    }

    /**
     *
     * @return array<int, mixed>
     */
    public function urls(): array
    {
        return collect($this->all())->pluck('urls')->flatten()->toArray();
    }

    public function process(Request $request, Response | JsonResponse $response): bool
    {
        $content = $response->getContent();
        if($content){
            [$filename, $_] = $this->getFileInfo($request);
            ProcessStaticContent::dispatch($content, $filename);
            return false;
        }
        return true;
    }


    /**
     *
     * @return array<int, string>
     */
    protected function getFileInfo(Request $request): array
    {
        $extention = $request->header('X-Inertia') !== 'true' ? 'html' : 'json';
        $pathParts = explode('/', trim($request->getPathInfo(), '/'));
        $filePart = array_pop($pathParts);
        $file = (strlen($filePart) ? $filePart : "index") . '.' . $extention;
        $relativePath = implode("/", $pathParts);

        return [$relativePath . "/" . $file, $extention];
    }

    protected function getContent(string $filename, string $extention): string
    {
        $seconds = config('staticsitegen.remember');
        return Cache::remember("ssg:$filename", $seconds, function() use($filename, $extention){
            $storate_name = config('staticsitegen.storage_name');
            // check if file exist
            if (!Storage::disk('html')->exists($filename)) {
                return false;
            }
            $content = Storage::disk('html')->get($filename);
            return $content;
        });
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
        return config('cache.driver') === 'file' ? throw_if(!app()->environment('production'), BadCacheConfigException::class) : true;
    }

    private function isStorageOk(): bool
    {
        $storage_name = config('staticsitegen.storage_name');
        return null === config("filesystems.disks.$storage_name") ? throw_if(!app()->environment('production'), StorageNotFoundException::class, $storage_name): true;

    }

}
