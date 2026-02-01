<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/v1/check
|
*/

Route::prefix('v1')->group(function () {
    // Versiya yoxlamaq üçün POST sorğusu
    Route::post('/check', [ApiController::class, 'checkUpdate']);
});
