<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\ChinaArea\Http\Controllers as WebController;

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
    Route::get('china-area', [WebController\ChinaAreaSettingController::class, 'index'])->name('china-area.index');
    Route::get('china-area/setting', [WebController\ChinaAreaSettingController::class, 'showSettingView'])->name('china-area.setting');
    Route::post('china-area/setting', [WebController\ChinaAreaSettingController::class, 'saveSetting']);
});

// without VerifyCsrfToken
// Route::withoutMiddleware([
//    \App\Http\Middleware\EncryptCookies::class,
//    \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('china-area', [WebController\ChinaAreaSettingController::class, 'index']);
// });
