<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\qr_codes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    /**
     * Generate a new QR code token
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'expired_date' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $token = Str::random(32);
        
        $qrCode = qr_codes::create([
            'token' => $token,
            'is_active' => true,
            'expired_date' => $validated['expired_date'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $qrCode,
            'message' => 'QR code generated successfully'
        ], 201);
    }

    /**
     * Get all QR codes
     */
    public function index(Request $request)
    {
        $qrCodes = qr_codes::query();

        if ($request->has('is_active')) {
            $qrCodes->where('is_active', $request->boolean('is_active'));
        }

        $qrCodes = $qrCodes->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $qrCodes,
        ]);
    }

    /**
     * Get a specific QR code
     */
    public function show($id)
    {
        $qrCode = qr_codes::find($id);

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR code not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $qrCode,
        ]);
    }
}
