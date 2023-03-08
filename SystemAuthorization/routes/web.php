<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\SystemAuthorization\Http\Controllers as WebController;

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

Route::prefix('system-authorization')
    ->middleware([\Plugins\SsoClient\Http\Middleware\SsoAuthCheck::class])
    ->withoutMiddleware([
        \App\Http\Middleware\EncryptCookies::class,
    ])
    ->group(function() {
    Route::resource('auth', WebController\CustomerController::class);
    Route::redirect('', route('auth.index'));

    Route::resource('auth-codes', WebController\AuthCodeController::class);
});

// without VerifyCsrfToken
// Route::prefix('system-authorization')->group(function() {
//     Route::get('/', [WebController\SystemAuthorizationController::class, 'index']);
// })->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
