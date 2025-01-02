<?php

use App\Http\Controllers\API\Admin\Auth\LoginController;
use App\Http\Controllers\API\Admin\dashboard\dashboardController;
use App\Http\Controllers\API\Admin\order\OrderController;
use Illuminate\Support\Facades\Route;

// =========Route for Admin==========

Route::post('/admin/login', [LoginController::class, 'login']);

Route::group(['prefix' => '/admin', 'middleware' => ['auth:api', 'role:admin']], function () {
    Route::get('/dashboard', [dashboardController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'orderDetails']);
    Route::get('/orders/{order}/update-status', [OrderController::class, 'statusUpdate']);
    Route::get('/orders-status', [dashboardController::class, 'status']);

    Route::get('/logout', [LoginController::class, 'logout']);
});

Route::get('/orders-status', [dashboardController::class, 'status']);
