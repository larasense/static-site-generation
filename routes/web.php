<?php

use Illuminate\Support\Facades\Route;

use Larasense\StaticSiteGeneration\Http\Controllers\UserController;

Route::middleware('web')->get("/user/current", [UserController::class,'show'])->name('staticsitegen:current');
