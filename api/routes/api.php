<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users/login', [AuthController::class, 'login']);
Route::post('/users/logout', [AuthController::class, 'logout']);
Route::post('/users/register', [UsersController::class, 'register']);


Route::middleware(['auth:sanctum', 'user-access:user'])->group(function () {
    Route::get('/users/me', [AuthController::class, 'me']);
    Route::post('password/forgot-password', [ForgotPasswordController::class, 'sendResetLinkResponse'])->name('passwords.send');
    Route::post('password/reset', [ForgotPasswordController::class, 'sendResetResponse'])->name('passwords.reset');
});

Route::middleware(['auth:sanctum', 'user-access:admin'])->group(function () {
    Route::get('/users', [UsersController::class, 'index']);
    Route::get('/users/me', [AuthController::class, 'me']);
});

Route::middleware(['auth:sanctum', 'user-access:seller'])->group(function () {

});

Route::middleware(['auth:sanctum', 'user-access:admin', 'user-access:seller'])->group(function () {

});

