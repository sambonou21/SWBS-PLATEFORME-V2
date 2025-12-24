<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount_fcfa',
        'currency',
        'exchange_rate',
        'total_amount_currency',
        'payment_provider',
        'payment_reference',
        'payment_payload',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'total_amount_fcfa' => 'float',
        'exchange_rate' => 'float',
        'total_amount_currency' => 'float',
        'payment_payload' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}