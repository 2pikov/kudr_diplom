<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderConfirmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'confirmation_code',
        'order_data',
        'is_confirmed',
        'expires_at'
    ];

    protected $casts = [
        'order_data' => 'array',
        'is_confirmed' => 'boolean',
        'expires_at' => 'datetime'
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
