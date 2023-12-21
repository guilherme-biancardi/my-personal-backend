<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Products\AcessoryController;
use App\Http\Controllers\Products\CellphoneController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/user')->group(function () {
    Route::get('/activate/{hash}', [UserController::class, 'activate'])->name('user.activate');
    Route::post('/send-activation-link', [UserController::class, 'sendActivationLink']);
    Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
    Route::get('/me', [UserController::class, 'me'])->middleware('jwt.verify');
});

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->middleware('jwt.verify');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.verify');
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('jwt.verify')->group(function () {
    Route::prefix('/sellers')->group(function () {
        Route::get('/', [SellerController::class, 'index']);
        Route::post('/create', [SellerController::class, 'store']);
        Route::delete('/delete', [SellerController::class, 'remove']);
        Route::post('/restore', [SellerController::class, 'restore']);
        Route::patch('/edit', [SellerController::class, 'update']);
    });
});
