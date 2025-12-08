<?php

namespace App\Http\Controllers;

use App\Models\qr_codes;
use Illuminate\Http\Request;

class ScanBarcodeController extends Controller
{
    public function index()
    {
        $qr_code = qr_codes::where('is_active', true)->first();

        return view('admin.scan-barcode', compact('qr_code'));
    }
}
