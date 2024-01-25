<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/auth', [AuthController::class, 'authenticate']);
Route::post('/register', [RegisterController::class, 'register'])->withoutMiddleware('custom.jwt');
Route::get('/products', [ProductController::class, 'index'])->withoutMiddleware('custom.jwt')
;
Route::middleware('custom.jwt')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::post('/cart', [CartController::class, 'create']);
});
