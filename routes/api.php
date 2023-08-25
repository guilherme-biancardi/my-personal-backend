<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CellphoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\JWTMiddleware;

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

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.verify');
    Route::get('/me', [AuthController::class, 'me'])->middleware('jwt.verify');
});

Route::middleware('jwt.verify')->group(function () {
    Route::prefix('cellphones')->group(function () {
        Route::get('/', [CellphoneController::class, 'index']);
        Route::post('/create', [CellphoneController::class, 'store']);
    });
});
