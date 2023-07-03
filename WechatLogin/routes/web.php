<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\WechatLogin\Http\Controllers as WebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('wechat-login')->group(function() {
    Route::get('/', [WebController\WechatLoginSettingController::class, 'index'])->name('wechat-login.index');
    Route::get('setting', [WebController\WechatLoginSettingController::class, 'showSettingView'])->name('wechat-login.setting');
    Route::post('setting', [WebController\WechatLoginSettingController::class, 'saveSetting']);
});

// without VerifyCsrfToken
// Route::prefix('wechat-login')->withoutMiddleware([
//     \App\Http\Middleware\EncryptCookies::class,
//     \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('/', [WebController\WechatLoginSettingController::class, 'index']);
// });
