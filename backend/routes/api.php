<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::get('/profile', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::patch('/profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
    Route::post('/send-profile-email-otp', [AuthController::class, 'sendProfileEmailOtp'])->middleware('auth:sanctum');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');

    Route::post('/send-register-otp', [AuthController::class, 'sendRegisterOtp']);
    Route::post('/send-forgot-password-otp', [AuthController::class, 'sendForgotPasswordOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks/trash', [TaskController::class, 'trash']);
    Route::post('/tasks/{task}/restore', [TaskController::class, 'restore']);
    Route::delete('/tasks/{task}/force-delete', [TaskController::class, 'forceDestroy']);
    Route::apiResource('tasks', TaskController::class);
});
Route::get('/dashboard', DashboardController::class)->middleware('auth:sanctum');

Route::get('/hello', function () {
    return response()->json([
        'message' => 'Hello from BSIT 4B!',
    ]);
});

Route::get('/introduce', function () {
    return response()->json([
        'message' => 'Hello from Jerson Jay Bonghanoy!',
    ]);
});
