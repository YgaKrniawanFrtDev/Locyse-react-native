<?php

use App\Http\Controllers\Api\QrCodeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\BarcodeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // QR Code endpoints
    Route::post('/qr-codes/generate', [QrCodeController::class, 'generate']);
    Route::get('/qr-codes/{id}', [QrCodeController::class, 'show']);
    Route::get('/qr-codes', [QrCodeController::class, 'index']);
    
    // Barcode endpoints
    Route::get('/barcodes/{id}/image', [BarcodeController::class, 'generateBarcode'])->name('barcode.image');
    Route::get('/barcodes/{id}/html', [BarcodeController::class, 'generateBarcodeHtml'])->name('barcode.html');
    Route::get('/barcodes/{id}/base64', [BarcodeController::class, 'getBarcodeBase64'])->name('barcode.base64');
    
    // Attendance endpoints
    Route::post('/attendance/scan', [AttendanceController::class, 'scan']);
    Route::get('/attendance/user/{userId}', [AttendanceController::class, 'getUserAttendance']);
    Route::get('/attendance/today', [AttendanceController::class, 'getTodayAttendance']);
});
