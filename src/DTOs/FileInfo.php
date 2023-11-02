<?php

namespace Larasense\StaticSiteGeneration\DTOs;

use Illuminate\Support\Facades\Storage;

/**
 * @property int $timestamp
 */
class FileInfo
{
    public function __construct(
        public readonly string $filename,
        public readonly string $extention,
    ) {
    }

    public function __get(string $name): mixed
    {
        switch ($name) {
            case 'timestamp':
                # code...
                /** @var string */
                $disk = config('staticsitegen.storage_name');
                $storage = Storage::disk($disk);
                if (!$storage->exists($this->filename)) {
                    return now()->timestamp;
                }
                return $storage->lastModified($this->filename);

        }
        return false;
    }
}
