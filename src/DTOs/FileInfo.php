<?php

namespace Larasense\StaticSiteGeneration\DTOs;

class FileInfo
{
    public function __construct(
        public readonly string $filename,
        public readonly string $extention,
    ){}
}
