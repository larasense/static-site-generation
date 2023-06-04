<?php

namespace Larasense\StaticSiteGeneration\DTOs;

use Illuminate\Support\Facades\Storage;

class FileInfo
{
    public int $timestamp;

    public function __construct(
        public readonly string $filename,
        public readonly string $extention,
    ){
        /** @var string */
        $disk = config('staticsitegen.storage_name');
        $this->timestamp = Storage::disk($disk)->lastModified($filename);
    }
}
