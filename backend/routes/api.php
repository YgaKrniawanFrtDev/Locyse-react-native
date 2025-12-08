<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScanController;

Route::post('/scan', [ScanController::class, 'scan']);
