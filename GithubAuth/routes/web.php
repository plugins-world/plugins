<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\GithubAuth\Http\Controllers as WebController;
use Plugins\GithubAuth\Http\Controllers\Web as Web;

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

Route::group([], function() {
    Route::get('github-auth', [WebController\GithubAuthSettingController::class, 'index'])->name('github-auth.index');
    Route::get('github-auth/setting', [WebController\GithubAuthSettingController::class, 'showSettingView'])->name('github-auth.setting');
    Route::post('github-auth/setting', [WebController\GithubAuthSettingController::class, 'saveSetting']);

    Route::get('github-auth/auth', [Web\GithubAuthController::class, 'index'])->name('github-auth.auth.index');
    Route::get('github-auth/auth-redirect', [Web\AuthController::class, 'redirect'])->name('github-auth.auth.redirect');
    Route::get('github-auth/auth-callback', [Web\AuthController::class, 'callback'])->name('github-auth.auth.callback');
});

// without VerifyCsrfToken
// Route::withoutMiddleware([
//    \App\Http\Middleware\EncryptCookies::class,
//    \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('github-auth', [WebController\GithubAuthSettingController::class, 'index']);
// });
