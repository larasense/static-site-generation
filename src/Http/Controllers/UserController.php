<?php

namespace Larasense\StaticSiteGeneration\Http\Controllers;

use Illuminate\Foundation\Auth\User;


class UserController
{
    public function show():User|null
    {
        return auth()->user();
    }
}
