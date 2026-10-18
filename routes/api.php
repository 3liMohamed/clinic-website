<?php

use App\Http\Middleware\VerifyApiKey;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')
    ->middleware(VerifyApiKey::class)
    ->group(function () {

        Route::get('/test', function () {
            return response()->json([
                'success' => true,
                'message' => 'Clinic API is working!',
                'version' => 'v1',
            ]);
        });

    });
