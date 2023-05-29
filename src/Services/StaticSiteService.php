<?php declare(strict_types = 1);
namespace Larasense\StaticSiteGeneration\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Route;
use Larasense\StaticSiteGeneration\Facades\Metadata;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Larasense\StaticSiteGeneration\Jobs\ProcessStaticContent;

class StaticSiteService
{
    public function get(Request $request): bool | Response
    {
        $route = $request->route();
        if (!$route instanceof Route){
            return false;
        }
        $metadata = Metadata::get($route);
        if (!$metadata) {
            return false;
        }
        // SSG metadata found
        if ($request->getMethod() !== 'GET' || !is_null($request->input('no-cache'))) {
            return false;
        }

        $filename = $this->getFilename($request);
        // check if file exist
        if (!Storage::disk('html')->exists($filename)) {
            return false;
        }

        $extention = $request->header('X-Inertia') !== 'true' ? 'html' : 'json';
        $content = Storage::disk('html')->get($filename);
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
     * @return array{array{'uri':string,'path':string,'controller':string,'method':string,'urls':string|array<int,string>}}
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
            $filename = $this->getFilename($request);
            ProcessStaticContent::dispatch($content, $filename);
            return false;
        }
        return true;
    }


    protected function getFilename(Request $request): string
    {

        $extention = $request->header('X-Inertia') !== 'true' ? 'html' : 'json';
        $pathParts = explode('/', trim($request->getPathInfo(), '/'));
        $filePart = array_pop($pathParts);
        $file = (strlen($filePart) ? $filePart : "index") . '.' . $extention;
        $relativePath = implode("/", $pathParts);

        return $relativePath . "/" . $file;
    }

}
