<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrCodesController extends Controller
{
    public function index()
    {
        return view('admin.qr-codes');
    }
}
