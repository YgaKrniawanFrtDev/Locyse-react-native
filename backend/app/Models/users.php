<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the attendances for the user.
     */
    public function attendances()
    {
        return $this->hasMany(attendance::class, 'user_id');
    }
}
