<?php

use App\Http\Controllers\API\Driver\Auth\LoginController;
use App\Http\Controllers\API\Driver\Notifications\DriverNotificationController;
use App\Http\Controllers\API\Driver\OrderController;
use App\Http\Controllers\API\User\UserController;
use Illuminate\Support\Facades\Route;

// ==========Route for Driver==========

Route::prefix('/driver')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('driver.login');
    Route::post('/register', [LoginController::class, 'register'])->name('driver.register');

    // auth middleware for driver
    Route::middleware(['auth:api', 'role:driver'])->group(function () {

        Route::get('/details', [LoginController::class, 'show']);
        Route::post('/profile-update', [UserController::class, 'update']);
        Route::post('/profile-photo/update', [UserController::class, 'updateProfilePhoto']);
        Route::post('/change-password', [LoginController::class, 'changePassword']);

        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'index');
            Route::get('/orders/details', 'show');
            Route::post('/orders/status-update', 'statusUpdate');
        });

        Route::get('/notifications', [DriverNotificationController::class, 'index']);
        Route::post('/notifications', [DriverNotificationController::class, 'store']);
        Route::post('/notifications/{notification}', [DriverNotificationController::class, 'update']);
        Route::delete('/notifications/{notification}', [DriverNotificationController::class, 'delete']);

        Route::get('/logout', [LoginController::class, 'logout']);
    });
});
