<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\LaravelLocalStorage\Http\Controllers as ApiController;

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

// Route::middleware('auth:api')->get('/laravel-local-storage', function (Request $request) {
//     return $request->user();
// });

// Route::prefix('laravel-local-storage')->group(function() {
//     Route::get('/', [ApiController\LaravelLocalStorageController::class, 'index']);
// });

Route::prefix('file')->group(function() {
    Route::post('upload', [ApiController\ApiController::class, 'upload']);
});

