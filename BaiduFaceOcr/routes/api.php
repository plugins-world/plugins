<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\BaiduFaceOcr\Http\Controllers as ApiController;

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

// Route::middleware('auth:api')->get('baidu-face-ocr', function (Request $request) {
//     return $request->user();
// });

// Route::group([], function() {
//     Route::get('baidu-face-ocr', [ApiController\BaiduFaceOcrSettingController::class, 'index']);
// });
Route::group([], function() {
    Route::post('baidu-face-ocr/face/verify', [ApiController\BaiduFaceOcrController::class, 'faceVerify']);
});
