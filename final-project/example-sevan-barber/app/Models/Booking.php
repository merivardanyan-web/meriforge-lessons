<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    public const STATUSES = ['booked' => 'Ամրագրված', 'done' => 'Կատարված', 'cancelled' => 'Չեղարկված'];

    /** ⭐ Ոչ մի $fillable․ ամրագրումը ստեղծում է միայն BookingService-ը՝ դաշտ առ դաշտ */
    protected $fillable = [];

    protected function casts(): array
    {
        return [
            'price'        => 'integer',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }

    /** withTrashed․ ջնջված ծառայության անունը պատմության մեջ մնում է */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class)->withTrashed();
    }

    public function isBooked(): bool
    {
        return $this->status === 'booked';
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /** Ակտիվ և դեռ չսկսված */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 'booked')->whereHas('slot', fn ($q) => $q->where('starts_at', '>', now()));
    }
}
