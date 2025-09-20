<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
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

        // Medical record management
        Route::get('/patients/{patient}/orders/{order}/edit', [App\Http\Controllers\Admin\MedicalRecordController::class, 'edit'])->name('medical-records.edit');
        Route::put('/patients/{patient}/orders/{order}', [App\Http\Controllers\Admin\MedicalRecordController::class, 'update'])->name('medical-records.update');

        // Normal values management
        Route::get('/admin/normal-values', [App\Http\Controllers\Admin\MedicalRecordController::class, 'normalValues'])->name('admin.normal-values');
        Route::put('/admin/normal-values', [App\Http\Controllers\Admin\MedicalRecordController::class, 'updateNormalValues'])->name('admin.normal-values.update');
    });

    // User routes
    Route::get('/health-check/{shareId}', [PatientController::class, 'showHealthCheck'])->name('health-check');
    Route::get('/my-health', [PatientController::class, 'myHealth'])->name('my-health');

    // Profile routes
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::delete('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
});