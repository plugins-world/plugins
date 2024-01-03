<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\BaiduFaceOcr\Http\Controllers as WebController;

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
    Route::get('baidu-face-ocr', [WebController\BaiduFaceOcrSettingController::class, 'index'])->name('baidu-face-ocr.index');
    Route::get('baidu-face-ocr/setting', [WebController\BaiduFaceOcrSettingController::class, 'showSettingView'])->name('baidu-face-ocr.setting');
    Route::post('baidu-face-ocr/setting', [WebController\BaiduFaceOcrSettingController::class, 'saveSetting']);
});

// without VerifyCsrfToken
// Route::withoutMiddleware([
//    \App\Http\Middleware\EncryptCookies::class,
//    \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('baidu-face-ocr', [WebController\BaiduFaceOcrSettingController::class, 'index']);
// });
