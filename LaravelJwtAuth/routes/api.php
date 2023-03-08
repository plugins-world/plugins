<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Plugins\LaravelJwtAuth\Http\Controllers as ApiController;

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

// Route::middleware('auth:api')->get('/laravel-jwt-auth', function (Request $request) {
//     return $request->user();
// });

Route::prefix(config('laravel-jwt-auth.route.prefix', 'auth'))->group(function() {
    Route::middleware(['auth:api'])->group(function () {
        Route::post('login', [ApiController\UserAuthController::class, 'login'])->withoutMiddleware(['auth:api']);
        Route::post('logout', [ApiController\UserAuthController::class, 'logout']);
        Route::post('refresh', [ApiController\UserAuthController::class, 'refresh']);
        Route::get('me', [ApiController\UserAuthController::class, 'me']);
    });

    Route::prefix('admin')->middleware(['auth:api-admin'])->group(function () {
        Route::post('login', [ApiController\AdministratorAuthController::class, 'login'])->withoutMiddleware(['auth:api-admin']);
        Route::post('logout', [ApiController\AdministratorAuthController::class, 'logout']);
        Route::post('refresh', [ApiController\AdministratorAuthController::class, 'refresh']);
        Route::get('me', [ApiController\AdministratorAuthController::class, 'me']);
    });
});
