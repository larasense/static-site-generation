<?php

namespace Larasense\StaticSiteGeneration\Exceptions;

use Spatie\Ignition\Contracts\Solution;
use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\ProvidesSolution;

class StorageNotFoundException extends SSGException implements ProvidesSolution
{
    public function __construct(protected string $storage_name){
        parent::__construct("In order to generate the HTML and JSON static files, we need to know where to store them. Please add a disk configuration for this purpouse in `config/filesystems.php` ");
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create("Add `{$this->storage_name}` to `config/filesystems.php`. ")
            ->setSolutionDescription("The html's and json's file should be stored needs to be defined")
            ->setDocumentationLinks([
                'Check out some examples `here`' => 'https://github.com/larasense/static-site-generation/blob/main/docs/storage-disk-configuration.md',
            ]);
    }
}
