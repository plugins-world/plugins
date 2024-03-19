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

Route::middleware(['auth:sanctum'])->group(function() {
   // 微信 jssdk
    Route::post('wechat-config/get-jssdk-config', [ApiController\WechatController::class, 'getJssdkConfig'])->withoutMiddleware(['auth:sanctum']);

    // 公众号登录
    Route::post('wechat-login/official-login/get-auth-url', [ApiController\WechatController::class, 'wechatAuthUrl'])->withoutMiddleware(['auth:sanctum']);
    Route::get('wechat-login/official-login/callback', [ApiController\WechatController::class, 'wechatAuthCallback'])->name('wechat-official-login.callback')->withoutMiddleware('auth:sanctum');
    Route::post('wechat-login/official-login/login-by-code', [ApiController\WechatController::class, 'wechatLoginByCode'])->withoutMiddleware(['auth:sanctum']);

    // 小程序登录
    Route::post('wechat-login/mini-app-login/login-by-code', [ApiController\WechatController::class, 'miniAppLoginByCode'])->withoutMiddleware(['auth:sanctum']);
    Route::post('wechat-login/mini-app-login/userinfo/bind-phone', [ApiController\WechatController::class, 'miniAppBindPhone'])->withoutMiddleware(['auth:sanctum']);
    Route::post('wechat-login/mini-app-login/userinfo/update', [ApiController\WechatController::class, 'miniAppUpdateUserInfo']);
});
