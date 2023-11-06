<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\EasyMap\Http\Controllers as WebController;

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
    Route::get('/', [WebController\EasyMapSettingController::class, 'index'])->name('index');
    Route::get('setting', [WebController\EasyMapSettingController::class, 'showSettingView'])->name('setting');
    Route::post('setting', [WebController\EasyMapSettingController::class, 'saveSetting']);
});

// without VerifyCsrfToken
// Route::withoutMiddleware([
//    \App\Http\Middleware\EncryptCookies::class,
//    \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('/', [WebController\EasyMapSettingController::class, 'index']);
// });
