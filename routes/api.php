<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HealthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('health', [HealthController::class, 'index']);

    Route::get('auth/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
