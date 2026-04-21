<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // ✅ Méthodes pour vérifier le rôle
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGerant(): bool
    {
        return $this->role === 'gerant';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }
}
