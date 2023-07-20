<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\PayCenter\Http\Controllers as ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('pay-center', function (Request $request) {
//     return $request->user();
// });

// Route::group([], function() {
//     Route::get('pay-center', [ApiController\PayCenterController::class, 'index']);
// });

Route::group([], function() {
    Route::any('pay-center/callback/wechatpay', [ApiController\PayCenterCallbackController::class, 'wechatPayCallback'])->name('pay-center.callback.wechatpay');
    Route::any('pay-center/callback/alipay', [ApiController\PayCenterCallbackController::class, 'aliPayCallback'])->name('pay-center.callback.alipay');
    Route::any('pay-center/callback/unipay', [ApiController\PayCenterCallbackController::class, 'uniPayCallback'])->name('pay-center.callback.unipay');


    Route::any('pay-center/wechatpay/{payType}', [ApiController\PayCenterController::class, 'pay'])->name('pay-center.pay');
});
