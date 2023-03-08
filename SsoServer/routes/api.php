<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\SsoServer\Http\Controllers as ApiController;
use Plugins\SsoServer\Http\Middleware\SsoAuthCheck;

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

// Route::middleware('auth:api')->get('/sso-server', function (Request $request) {
//     return $request->user();
// });

Route::prefix('sso-server')->middleware([
    SsoAuthCheck::class,
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
])->group(function() {
    Route::get('get-config', [ApiController\ApiController::class, 'getConfig'])->name('sso-server.api.public_key')->withoutMiddleware([SsoAuthCheck::class]);
    Route::post('get-userinfo', [ApiController\ApiController::class, 'getUserInfo'])->name('sso-server.api.userinfo');
    Route::post('validate', [ApiController\ApiController::class, 'ssoValidate'])->name('sso-server.api.validate');
});
