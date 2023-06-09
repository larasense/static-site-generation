<?php

namespace Larasense\StaticSiteGeneration\DTOs;

use Illuminate\Support\Facades\Storage;

class FileInfo
{
    public function __construct(
        public readonly string $filename,
        public readonly string $extention,
    ){
    }

    public function __get(string $name): mixed
    {
        switch ($name) {
            case 'timestamp':
                # code...
                /** @var string */
                $disk = config('staticsitegen.storage_name');
                if (!Storage::disk($disk)->exists($this->filename)){
                    return now()->timestamp;
                }
                return Storage::disk($disk)->lastModified($this->filename);

        }
        return false;
    }
}
