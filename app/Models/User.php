<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'username',
        'password',
        'role',
        'last_login',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login'        => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function organizer()
    {
        return $this->hasOne(Organizer::class, 'user_id');
    }
}
