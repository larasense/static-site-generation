<?php

namespace Larasense\StaticSiteGeneration\Services;

class File
{
    public function get(string $filename): false|string
    {
        return file_get_contents($filename);
    }

    public function set(string $filename, string $content): void
    {
        file_put_contents($filename, $content);
    }
}
