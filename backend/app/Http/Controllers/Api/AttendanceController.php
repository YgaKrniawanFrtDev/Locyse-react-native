<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\attendance;
use App\Models\qr_codes;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Scan barcode and record attendance
     */
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:masuk,pulang',
        ]);

        try {
            DB::beginTransaction();

            // Find QR code by token
            $qrCode = qr_codes::where('token', $validated['token'])->first();

            if (!$qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code not found'
                ], 404);
            }

            // Check if QR code is active
            if (!$qrCode->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code is not active'
                ], 403);
            }

            // Check if QR code is expired
            if (now() > $qrCode->expired_date) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code has expired'
                ], 403);
            }

            // Check if user exists
            $user = users::find($validated['user_id']);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Check for duplicate scan (same user, same status, same day)
            $today = now()->startOfDay();
            $existingAttendance = attendance::where('user_id', $validated['user_id'])
                ->where('status', $validated['status'])
                ->where('qr_code_id', $qrCode->id)
                ->whereDate('scanned_at', $today)
                ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already scanned for ' . $validated['status'] . ' today'
                ], 409);
            }

            // Create attendance record
            $attendance = attendance::create([
                'user_id' => $validated['user_id'],
                'qr_code_id' => $qrCode->id,
                'status' => $validated['status'],
                'scanned_at' => now(),
            ]);

            DB::commit();

            // Broadcast event for real-time update
            broadcast(new \App\Events\AttendanceScanned($attendance->load('user', 'qrCode')));

            return response()->json([
                'success' => true,
                'data' => $attendance->load('user', 'qrCode'),
                'message' => 'Attendance recorded successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error recording attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user attendance records
     */
    public function getUserAttendance($userId)
    {
        $user = users::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $attendances = attendance::where('user_id', $userId)
            ->with('qrCode')
            ->orderBy('scanned_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
    }

    /**
     * Get today's attendance records
     */
    public function getTodayAttendance()
    {
        $today = now()->startOfDay();
        
        $attendances = attendance::whereDate('scanned_at', $today)
            ->with('user', 'qrCode')
            ->orderBy('scanned_at', 'desc')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
    }
}
