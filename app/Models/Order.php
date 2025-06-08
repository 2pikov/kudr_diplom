<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'uid',
        'user_id',
        'number',
        'status',
        'name',
        'phone',
        'email',
        'address',
        'bonus_used',
        'bonus_earned'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'pid');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
} 