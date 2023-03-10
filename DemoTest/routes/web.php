<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\DemoTest\Http\Controllers as WebController;

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

Route::prefix('demo-test')->group(function() {
    Route::get('/', [WebController\DemoTestController::class, 'index'])->name('demo-test.index');
    Route::get('setting', [WebController\DemoTestController::class, 'showSettingView'])->name('demo-test.setting');
    Route::post('setting', [WebController\DemoTestController::class, 'saveSetting']);
});

// without VerifyCsrfToken
// Route::prefix('demo-test')->withoutMiddleware([
//     \App\Http\Middleware\EncryptCookies::class,
//     \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('/', [WebController\DemoTestController::class, 'index']);
// });