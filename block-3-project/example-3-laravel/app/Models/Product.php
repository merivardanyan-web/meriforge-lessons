<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Առաջադրանք 2-ի Product, PhysicalProduct և ProductRepository-ն՝ մեկ մոդելում — PHP Դաս 20 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    /** Mass assignment-ի սպիտակ ցուցակ — Դաս 20 */
    protected $fillable = [
        'category_id', 'name', 'slug', 'specs', 'description',
        'price', 'discount', 'stock', 'type', 'weight', 'image', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'integer',
            'discount'  => 'integer',
            'stock'     => 'integer',
            'weight'    => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // ---------- Կապեր ----------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ---------- Scope-ներ ----------

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** Առաջադրանք 1-ի buildWhere()-ը՝ Eloquent-ով։ Ամեն պայման՝ միայն եթե ֆիլտրը կա */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['category_id'] ?? null, fn (Builder $q, int $id) => $q->where('category_id', $id))
            ->when($filters['q'] ?? null, fn (Builder $q, string $text) => $q->where(fn (Builder $w) => $w
                ->where('name', 'like', '%' . addcslashes($text, '%_\\') . '%')
                ->orWhere('specs', 'like', '%' . addcslashes($text, '%_\\') . '%')))
            ->when(isset($filters['min']), fn (Builder $q) => $q->whereRaw('ROUND(price * (100 - discount) / 100) >= ?', [$filters['min']]))
            ->when(isset($filters['max']), fn (Builder $q) => $q->whereRaw('ROUND(price * (100 - discount) / 100) <= ?', [$filters['max']]))
            ->when(($filters['stock'] ?? 'all') === 'in', fn (Builder $q) => $q->where(fn (Builder $w) => $w->where('stock', '>', 0)->orWhere('type', 'digital')))
            ->when(($filters['stock'] ?? 'all') === 'sale', fn (Builder $q) => $q->where('discount', '>', 0));
    }

    // ---------- Դաս 12–13-ի մեթոդները ----------

    public function finalPrice(): int
    {
        return (int) round($this->price * (100 - $this->discount) / 100);
    }

    public function hasDiscount(): bool
    {
        return $this->discount > 0;
    }

    public function isDigital(): bool
    {
        return $this->type === 'digital';
    }

    public function inStock(): bool
    {
        return $this->isDigital() || $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        return ! $this->isDigital() && $this->stock > 0 && $this->stock <= 3;
    }

    public function maxQty(): int
    {
        return $this->isDigital() ? 5 : min(10, $this->stock);
    }

    public function shippingCost(): int
    {
        return $this->isDigital() ? 0 : ($this->weight > 5000 ? 3000 : 1000);
    }

    public function isNew(): bool
    {
        return $this->created_at?->gt(now()->subDays(30)) ?? false;
    }

    public function formattedPrice(): string
    {
        return money($this->finalPrice());
    }

    public function formattedOldPrice(): string
    {
        return money($this->price);
    }

    public function imageUrl(): string
    {
        // Ադմինից վերբեռնածը՝ storage/app/public/products, մնացածը՝ public/images — Դաս 21
        if ($this->image && str_starts_with($this->image, 'products/')) {
            return asset('storage/' . $this->image);
        }

        return asset('images/' . ($this->image ?? 'placeholder.jpg'));
    }

    /** Դաս 11-ի uniqueSlug()-ը՝ Դաս 20-ի ուղղմամբ (withTrashed) — Դաս 21 */
    public static function uniqueSlugFor(string $name, ?int $exceptId = null): string
    {
        $base = slugify($name) ?: 'product';
        $slug = $base;
        $i = 1;

        while (
            static::withTrashed()
                ->where('slug', $slug)
                ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId))
                ->exists()
        ) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
