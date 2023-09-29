<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\SmsAuth\Http\Controllers as WebController;

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
    Route::get('sms-auth', [WebController\SmsAuthSettingController::class, 'index'])->name('sms-auth.index');
    Route::get('sms-auth/setting', [WebController\SmsAuthSettingController::class, 'showSettingView'])->name('sms-auth.setting');
    Route::post('sms-auth/setting', [WebController\SmsAuthSettingController::class, 'saveSetting']);
});

// without VerifyCsrfToken
// Route::withoutMiddleware([
//    \App\Http\Middleware\EncryptCookies::class,
//    \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('sms-auth', [WebController\SmsAuthSettingController::class, 'index']);
// });
