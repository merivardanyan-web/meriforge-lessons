<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = ['admin' => 'Ադմին', 'staff' => 'Վարսավիր', 'client' => 'Հաճախորդ'];

    /** ⭐ role-ը $fillable-ում չէ․ գրանցման ֆորմային role=admin ավելացնելը ոչինչ չի տա */
    protected $fillable = ['name', 'email', 'phone', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /** Հաճախորդի ամրագրումները */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /** Վարսավիրի աշխատանքային ժամերը */
    public function slots(): HasMany
    {
        return $this->hasMany(TimeSlot::class, 'staff_id');
    }

    public function scopeStaff($query)
    {
        return $query->where('role', 'staff')->orderBy('name');
    }
}
