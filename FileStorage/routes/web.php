<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\FileStorage\Http\Controllers as WebController;
use Plugins\FileStorage\Http\Controllers as ApiController;

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

Route::prefix('file-storage')->group(function() {
    Route::get('/', [WebController\FileStorageSettingController::class, 'index'])->name('file-storage.index');
    Route::get('setting', [WebController\FileStorageSettingController::class, 'showSettingView'])->name('file-storage.setting');
    Route::post('setting', [WebController\FileStorageSettingController::class, 'saveSetting']);
});

// without VerifyCsrfToken
// Route::prefix('file-storage')->withoutMiddleware([
//     \App\Http\Middleware\EncryptCookies::class,
//     \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('/', [WebController\FileStorageController::class, 'index']);
// });
