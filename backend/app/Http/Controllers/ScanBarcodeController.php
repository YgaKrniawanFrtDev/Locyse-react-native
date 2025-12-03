<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanBarcodeController extends Controller
{
    public function index()
    {
        return view('admin.scan-barcode');
    }
}
