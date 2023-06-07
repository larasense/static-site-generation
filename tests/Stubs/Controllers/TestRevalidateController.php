<?php

namespace Larasense\StaticSiteGeneration\Tests\Stubs\Controllers;

use Illuminate\Routing\Controller;
use Larasense\StaticSiteGeneration\Attributes\SSG;

class TestRevalidateController extends Controller
{
    #[SSG(revalidate: 60)]
    public function index(): string
    {
        return 'index';
    }
}
