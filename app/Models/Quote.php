<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'name',
        'email',
        'phone',
        'company',
        'project_type',
        'budget_min',
        'budget_max',
        'currency',
        'message',
        'status',
        'admin_notes',
        'responded_at',
    ];

    protected $casts = [
        'budget_min' => 'float',
        'budget_max' => 'float',
        'responded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}