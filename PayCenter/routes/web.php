<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Support\Facades\Route;
use Plugins\PayCenter\Http\Controllers as WebController;

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
    Route::get('pay-center', [WebController\PayCenterSettingController::class, 'index'])->name('pay-center.index');
    Route::get('pay-center/setting', [WebController\PayCenterSettingController::class, 'showSettingView'])->name('pay-center.setting');
    Route::post('pay-center/setting', [WebController\PayCenterSettingController::class, 'saveSetting']);
    Route::post('pay-center/wechatpay/upload-File', [WebController\PayCenterSettingController::class, 'uploadFile'])->name('pay-center.wechatpay.upload-file');
    Route::get('pay-center/wechatpay/download-public-cert', [WebController\PayCenterSettingController::class, 'downloadPublicCert'])->name('pay-center.wechatpay.download-public-cert');
});

// without VerifyCsrfToken
// Route::withoutMiddleware([
//         \App\Http\Middleware\EncryptCookies::class,
//         \App\Http\Middleware\VerifyCsrfToken::class,
// ])->group(function() {
//     Route::get('pay-center', [WebController\PayCenterSettingController::class, 'index']);
// });
