<?php

namespace Larasense\StaticSiteGeneration\Http\Controllers;

use Illuminate\Http\JsonResponse;

class UserController
{
    public function show(): JsonResponse
    {
        $user = auth()->user();

        if(!$user) {
            return response()->json([]);
        }

        return response()->json([
            'user'=>$user,
            'updated_at' => $user->updated_at, // @phpstan-ignore-line
        ]);
    }
}
