<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\ChinaArea\Http\Controllers as ApiController;
use Plugins\ChinaArea\Http\Controllers\Api as Api;

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

// Route::middleware('auth:api')->get('china-area', function (Request $request) {
//     return $request->user();
// });

Route::group([], function () {
    // Route::get('china-area', [ApiController\ChinaAreaSettingController::class, 'index']);
    Route::get('china-area/areas', [Api\AreaController::class, 'index'])->withoutMiddleware(['auth:sanctum']);
});
