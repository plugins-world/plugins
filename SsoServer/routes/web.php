<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\SsoServer\Http\Controllers as WebController;

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

Route::prefix('sso-server')->withoutMiddleware([
    \App\Http\Middleware\EncryptCookies::class,
])->group(function() {
    Route::get('index', [WebController\SettingController::class, 'index'])->name('sso-server.index');
    Route::get('setting', [WebController\SettingController::class, 'showSettingPage'])->name('sso-server.setting');
    Route::post('setting', [WebController\SettingController::class, 'save']);

    Route::group([
        'middleware' => [\Plugins\SsoServer\Http\Middleware\SsoAuthCheck::class],
    ], function () {
        // 登录
        Route::get('/login', [WebController\AuthController::class, 'showLoginForm'])->name('sso-server.login')->withoutMiddleware(\Plugins\SsoServer\Http\Middleware\SsoAuthCheck::class);
        Route::post('/login', [WebController\AuthController::class, 'login'])->withoutMiddleware(\Plugins\SsoServer\Http\Middleware\SsoAuthCheck::class);

        // 退出
        Route::get('/logout', [WebController\AuthController::class, 'logout'])->name('sso-server.logout')->withoutMiddleware(\Plugins\SsoServer\Http\Middleware\SsoAuthCheck::class);

        // 注册
        Route::get('/register', [WebController\AuthController::class, 'showRegisterForm'])->name('sso-server.register')->withoutMiddleware(\Plugins\SsoServer\Http\Middleware\SsoAuthCheck::class);
        Route::post('/register', [WebController\AuthController::class, 'register'])->withoutMiddleware(\Plugins\SsoServer\Http\Middleware\SsoAuthCheck::class);
     
        // sso 登录后的首页
        Route::get('', [WebController\AuthController::class, 'index'])->name('sso-server.index');

        // sso web 服务
        Route::get('/sso', [WebController\AuthController::class, 'sso'])->name('sso-server.service');
    });
});

// without VerifyCsrfToken
// Route::prefix('sso-server')->withoutMiddleware([
//     \App\Http\Middleware\EncryptCookies::class,
//     \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('/', [WebController\SsoServerController::class, 'index']);
// });
