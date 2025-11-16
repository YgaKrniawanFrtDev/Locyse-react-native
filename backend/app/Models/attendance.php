<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    protected $fillable = [
        'user_id',
        'qr_code_id',
        'status',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    /**
     * Get the user that owns the attendance.
     */
    public function user()
    {
        return $this->belongsTo(users::class, 'user_id');
    }

    /**
     * Get the QR code that owns the attendance.
     */
    public function qrCode()
    {
        return $this->belongsTo(qr_codes::class, 'qr_code_id');
    }
}
