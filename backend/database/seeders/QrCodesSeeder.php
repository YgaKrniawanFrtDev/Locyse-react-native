<?php

namespace Database\Seeders;

use App\Models\qr_codes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QrCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $qrCodes = [];
        
        // Generate 5 QR codes for today
        for ($i = 0; $i < 5; $i++) {
            $qrCodes[] = [
                'token' => Str::random(32),
                'is_active' => true,
                'expired_date' => now()->addHours(8),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        qr_codes::insert($qrCodes);
    }
}
