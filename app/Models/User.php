<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory&lt;\Database\Factories\UserFactory&gt; */
    use HasFactory, Notifiable;

    /**
     * Attributs assignables.
     *
     * @var list&lt;string&gt;
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company',
        'role',
        'locale',
        'currency',
    ];

    /**
     * Attributs masqués.
     *
     * @var list&lt;string&gt;
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts.
     *
     * @return array&lt;string, string&gt;
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}