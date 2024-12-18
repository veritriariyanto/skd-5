<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'token',
        'validity',
        'expired',
        'no_times_generated',
        'no_times_attempted',
        'generated_at',
    ];

    protected $casts = [
        'expired' => 'boolean',
        'generated_at' => 'datetime',
    ];
}