<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'price',
        'img',
        'category_id',
        'country',
        'color',
        'qty',
        'weight',
        'obiem',
        'osnova',
        'time',
        'tempa',
        'srok_godnosti',
        'dop_info',
        'rashod'
    ];

    protected $appends = ['average_rating'];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comparisons()
    {
        return $this->hasMany(Comparison::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'pid');
    }
} 