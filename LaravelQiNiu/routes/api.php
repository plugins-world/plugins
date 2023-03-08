<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\LaravelQiNiu\Http\Controllers as Controller;

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

// Route::middleware('auth:api')->get('/laravel-qiniu', function (Request $request) {
//     return $request->user();
// });

Route::prefix('qiniu')->group(function() {
    Route::get('token', [Controller\ApiController::class, 'getToken']);
    Route::post('upload', [Controller\ApiController::class, 'upload']);
});
