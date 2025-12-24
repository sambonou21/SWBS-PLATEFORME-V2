<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $table = 'portfolio';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'service_type',
        'client_name',
        'image_path',
        'url',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_type', 'slug');
    }
}