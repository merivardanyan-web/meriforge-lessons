<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TimeSlot extends Model
{
    public const LENGTH_MIN = 60;      // մեկ ժամ․ ծառայությունները 60 րոպեից երկար չեն

    protected $fillable = ['staff_id', 'starts_at'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime'];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /** Բոլոր ամրագրումները՝ չեղարկվածներն էլ (պատմություն) */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /** Ակտիվ ամրագրումը՝ active_slot_id-ով։ Առավելագույնը մեկ է՝ UNIQUE-ի շնորհիվ */
    public function activeBooking(): HasOne
    {
        return $this->hasOne(Booking::class, 'active_slot_id');
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->whereDoesntHave('activeBooking');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>', now());
    }

    /** Ամրագրման օրերը՝ այսօրից 7 օր․ ['2026-11-02' => Carbon, ...] */
    public static function bookingDays(): array
    {
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = today()->addDays($i);
            $days[$day->toDateString()] = $day;
        }

        return $days;
    }

    /** Ազատ ժամերը տվյալ օրը՝ բոլոր վարսավիրների կամ մեկի։ Նույն հարցումը՝ էջում և API-ում */
    public static function freeFor(CarbonInterface $day, ?int $staffId = null)
    {
        return static::query()
            ->free()
            ->upcoming()
            ->onDate($day)
            ->when($staffId, fn ($q) => $q->where('staff_id', $staffId))
            ->with('staff:id,name')
            ->orderBy('starts_at')
            ->orderBy('staff_id')
            ->get();
    }

    public function scopeOnDate(Builder $query, CarbonInterface $day): Builder
    {
        return $query->whereBetween('starts_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()]);
    }
}
