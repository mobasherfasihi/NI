<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

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
Route::post('auth', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [UserController::class, 'logout']);

    Route::prefix('user')->group(function() {
        Route::get('', [UserController::class, 'authDetails']);
        Route::get('products', [UserController::class, 'products']);
        Route::post('products', [UserController::class, 'purchase']);
        Route::delete('products/{product:sku}', [UserController::class, 'deleteProduct']);
    });

    Route::get('products', [ProductController::class, 'index']);
});
