<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\SanctumAuth\Http\Controllers\Api as ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/sanctum-auth', function (Request $request) {
//     return $request->user();
// });

Route::prefix('sanctum-auth')->group(function() {
    Route::post('/register', [ApiController\AuthController::class, 'register']);
    Route::post('/login', [ApiController\AuthController::class, 'login']);
    Route::delete('/logout', [ApiController\AuthController::class, 'logout']);
});
