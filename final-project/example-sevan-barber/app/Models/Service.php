<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    /** slug-ը ձևավորվում է կոնտրոլերում, ոչ ֆորմայից */
    protected $fillable = ['name', 'description', 'duration_min', 'price', 'is_active'];

    protected function casts(): array
    {
        return [
            'duration_min' => 'integer',
            'price'        => 'integer',
            'is_active'    => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** «Սանրվածք + մորուք» → sanrvatsq-moruq, իսկ կրկնության դեպքում՝ -2, -3 (ջնջվածներն էլ հաշվի առնելով) */
    public static function uniqueSlugFor(string $name, ?int $exceptId = null): string
    {
        $base = slugify($name);
        $slug = $base;

        for ($i = 2; static::withTrashed()->where('slug', $slug)->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId))->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }
}
