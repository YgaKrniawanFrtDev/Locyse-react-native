<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\qr_codes;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Generate barcode image from QR code token
     */
    public function generateBarcode($id)
    {
        $qrCode = qr_codes::find($id);

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR code not found'
            ], 404);
        }

        try {
            $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($qrCode->token, BarcodeGeneratorPNG::TYPE_CODE_128);

            return response($barcode)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'inline; filename="barcode-' . $qrCode->id . '.png"');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating barcode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate barcode HTML (SVG) for display
     */
    public function generateBarcodeHtml($id)
    {
        $qrCode = qr_codes::find($id);

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR code not found'
            ], 404);
        }

        try {
            $generator = new BarcodeGeneratorHTML();
            $barcode = $generator->getBarcode($qrCode->token, BarcodeGeneratorHTML::TYPE_CODE_128);

            return response($barcode)
                ->header('Content-Type', 'text/html; charset=utf-8');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating barcode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get barcode data as base64
     */
    public function getBarcodeBase64($id)
    {
        $qrCode = qr_codes::find($id);

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR code not found'
            ], 404);
        }

        try {
            $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($qrCode->token, BarcodeGeneratorPNG::TYPE_CODE_128);
            $base64 = base64_encode($barcode);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $qrCode->id,
                    'token' => $qrCode->token,
                    'barcode_base64' => 'data:image/png;base64,' . $base64,
                    'barcode_url' => route('api.barcode.image', $qrCode->id),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating barcode: ' . $e->getMessage()
            ], 500);
        }
    }
}
