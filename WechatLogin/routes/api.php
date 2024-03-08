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

Route::prefix('wechat-config')->group(function () {
    Route::post('get-jssdk-config', [ApiController\WechatConfigController::class, 'getJssdkConfig']);
});

Route::prefix('wechat-login')->middleware(['auth:sanctum'])->group(function() {
    // 公众号
    Route::post('official-login/get-auth-url', [ApiController\WechatLoginController::class, 'wechatAuthUrl'])->withoutMiddleware(['auth:sanctum']);
    Route::post('official-login/login-by-code', [ApiController\WechatLoginController::class, 'wechatLoginByCode'])->withoutMiddleware(['auth:sanctum']);

    // 小程序
    Route::post('mini-app-login/login-by-code', [ApiController\WechatLoginController::class, 'miniAppLoginByCode'])->withoutMiddleware(['auth:sanctum']);
    Route::post('mini-app-login/userinfo/bind-phone', [ApiController\WechatLoginController::class, 'miniAppBindPhone'])->withoutMiddleware(['auth:sanctum']);
    Route::post('mini-app-login/userinfo/update', [ApiController\WechatLoginController::class, 'miniAppUpdateUserInfo']);
});
