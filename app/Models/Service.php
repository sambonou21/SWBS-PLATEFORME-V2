<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'base_price_fcfa',
        'is_active',
        'image_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price_fcfa' => 'integer',
    ];

    public function portfolio()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}