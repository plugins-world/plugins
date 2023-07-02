<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\SanctumAuth\Http\Controllers as WebController;

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

Route::prefix('sanctum-auth')->middleware('plugin.auth')->group(function() {
    Route::get('/', [WebController\SanctumAuthController::class, 'index'])->name('sanctum-auth.index');
    Route::get('setting', [WebController\SanctumAuthController::class, 'showSettingView'])->name('sanctum-auth.setting');
    Route::post('setting', [WebController\SanctumAuthController::class, 'saveSetting']);
});

Route::get('/', function () {
    return [
        'Laravel' => app()->version()
    ];
});

// without VerifyCsrfToken
// Route::prefix('sanctum-auth')->withoutMiddleware([
//     \App\Http\Middleware\EncryptCookies::class,
//     \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('/', [WebController\SanctumAuthController::class, 'index']);
// });
