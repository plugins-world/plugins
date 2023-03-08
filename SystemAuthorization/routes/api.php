<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\SystemAuthorization\Http\Controllers\Api as ApiController;

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

// Route::middleware('auth:api')->get('/system-authorization', function (Request $request) {
//     return $request->user();
// });

Route::prefix('system-authorization')->group(function() {
    // Route::get('/', [ApiController\SystemAuthorizationController::class, 'index']);
    Route::get('get-rsa-key', [ApiController\SystemAuthorizationController::class, 'getRsaKeyByType']);
});
