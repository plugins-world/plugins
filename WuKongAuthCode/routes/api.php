<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\WuKongAuthCode\Http\Controllers as ApiController;

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

// Route::middleware('auth:api')->get('/wu-kong-auth-code', function (Request $request) {
//     return $request->user();
// });

Route::prefix('wu-kong-auth-code')->group(function() {
    // Route::get('/', [ApiController\WuKongAuthCodeController::class, 'index']);
    Route::post('auth-code/issue', [ApiController\WuKongAuthCodeController::class, 'authCodeIssue']);
    Route::delete('auth-code/revoke', [ApiController\WuKongAuthCodeController::class, 'authCodeRevoke']);
    Route::post('auth-code/validate', [ApiController\WuKongAuthCodeController::class, 'authCodeValidate']);
});
