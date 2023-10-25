<?php

namespace Larasense\StaticSiteGeneration\Tests\Stubs\Controllers;

use Illuminate\Routing\Controller;
use Larasense\StaticSiteGeneration\Attributes\SSG;

class TestPathController extends Controller
{
    #[SSG(path: 'getStaticPath')]
    public function show(string $id): string
    {
        return $id;
    }

    /** @return array<array<string,int>> */
    public static function getStaticPath(): array
    {
        return [
            [ 'id' => 1],
            [ 'id' => 2],
        ];
    }
}
