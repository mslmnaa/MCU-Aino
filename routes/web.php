<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('patients', PatientController::class);
        Route::get('/admin/health-records', [DashboardController::class, 'adminHealthRecords'])->name('admin.health-records');
    });

    // User routes
    Route::get('/health-check/{shareId}', [PatientController::class, 'showHealthCheck'])->name('health-check');
    Route::get('/my-health', [PatientController::class, 'myHealth'])->name('my-health');
});