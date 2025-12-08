<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\qr_codes;

class ScanController extends Controller
{
    public function scan(Request $request)
    {
        // Validasi input
        $request->validate([
            'token' => 'required'
        ]);

        // Cek token di database
        $qr = qr_codes::where('token', $request->token)->first();

        if (!$qr) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan'
            ], 404);
        }

        // Balikin response simple
        return response()->json([
            'success' => true,
            'message' => 'Token valid',
            'qr_code' => $qr
        ]);
    }
}
