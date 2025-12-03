<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScanBarcodeController;
use Illuminate\Support\Facades\Route;
 
// Admin panel routes
Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/scan-barcode', [ScanBarcodeController::class, 'index'])->name('scan-barcode.index');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
});
