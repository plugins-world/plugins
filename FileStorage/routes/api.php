<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\FileStorage\Http\Controllers as ApiController;

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

// Route::middleware('auth:api')->get('/file-storage', function (Request $request) {
//     return $request->user();
// });

Route::prefix('file-storage')->group(function() {
    // Route::get('/', [ApiController\FileStorageController::class, 'index']);

    Route::post('file/upload', [ApiController\FileStorageController::class, 'fileUpload'])->name('file.upload');
    Route::post('file/uploadFinished', [ApiController\FileStorageController::class, 'fileUploadFinished'])->name('file.uploadFinished');

    Route::get('file/download/{filename}', [ApiController\FileStorageController::class, 'fileDownload'])->name('file.download')->middleware('signed');
    Route::get('file/view/{filename}', [ApiController\FileStorageController::class, 'fileView'])->name('file.view');
});
