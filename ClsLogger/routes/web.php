<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\ClsLogger\Http\Controllers\SettingController;

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

Route::prefix('cls-logger')->name('cls-logger.')->group(function() {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::get('settings', [SettingController::class, 'showSettingView'])->name('setting');
    Route::post('setting', [SettingController::class, 'saveSetting'])->name('save.setting');

    // without VerifyCsrfToken
    // Route::withoutMiddleware([
    //    \App\Http\Middleware\EncryptCookies::class,
    //    \App\Http\Middleware\VerifyCsrfToken::class,
    // ])->group(function() {
    //     Route::get('example', [SettingController::class, 'index'])->name('example');
    // });
});
