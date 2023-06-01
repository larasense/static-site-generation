<?php

namespace Larasense\StaticSiteGeneration\DTOs;

class PageFile
{
    public function __construct(
        public readonly string $filename,
        public readonly string $extention,
    ){}
}
