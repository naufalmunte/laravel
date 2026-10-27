<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $table = 'email_otps';

    protected $fillable = [
        'email',
        'code_hash',
        'registration_data',
        'expires_at',
        'attempts',
    ];

    protected function casts(): array
    {
        return [
            'registration_data' => 'array',
            'expires_at' => 'datetime',
            'attempts' => 'integer',
        ];
    }
}
