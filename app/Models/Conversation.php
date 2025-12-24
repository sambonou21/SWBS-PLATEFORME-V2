<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prospect_name',
        'prospect_email',
        'prospect_phone',
        'prospect_session_id',
        'ip_address',
        'country',
        'status',
        'last_message_at',
        'is_prospect',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_prospect' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}