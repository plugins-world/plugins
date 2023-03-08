<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\FileManage\Http\Controllers as WebController;

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

Route::prefix('file-manage')->group(function() {
    Route::post('/login', [WebController\FileManageController::class, 'login']);

    Route::get('/', [WebController\FileManageController::class, 'home']);

    // Route::middleware('auth.basic:web,name')->group(function () {
        Route::get('/disk-directories', [WebController\FileManageController::class, 'diskDirectories']);
        Route::get('/data', [WebController\FileManageController::class, 'index']);
        Route::post('/transcode', [WebController\FileManageController::class, 'transcode']);
    // });
})
->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
;
