<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\WechatLogin\Http\Controllers as ApiController;

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

// Route::middleware('auth:api')->get('/wechat-login', function (Request $request) {
//     return $request->user();
// });

Route::prefix('wechat-login')->group(function() {
    Route::post('login/code', [ApiController\WechatLoginController::class, 'miniAppLoginCode']);
    Route::post('userinfo/bind-phone', [ApiController\WechatLoginController::class, 'miniAppBindPhone']);

    Route::group([
        'middleware' => ['auth:sanctum'],
    ],function () {
        Route::post('userinfo/update', [ApiController\WechatLoginController::class, 'miniAppUpdateUserInfo']);
    });
});
