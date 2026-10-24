<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /** ⭐ role-ը այստեղ ՉԿԱ (դասարանական, փորձ 3)։ Այս մեկ բառը տարբերությունն է անդամի և ադմինի միջև */
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',          // password_hash()-ը ինքնաբերաբար — Դաս 16
        ];
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'editor'], true);
    }
}
