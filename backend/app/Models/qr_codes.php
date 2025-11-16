<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class qr_codes extends Model
{
    protected $fillable = [
        'token',
        'is_active',
        'expired_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expired_date' => 'datetime',
    ];

    /**
     * Get the attendances for the QR code.
     */
    public function attendances()
    {
        return $this->hasMany(attendance::class, 'qr_code_id');
    }
}
