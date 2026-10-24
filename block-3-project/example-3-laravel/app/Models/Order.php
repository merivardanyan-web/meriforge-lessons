<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS = [
        'new'       => 'Նոր',
        'paid'      => 'Վճարված',
        'shipped'   => 'Ճանապարհին',
        'cancelled' => 'Չեղարկված',
    ];

    /** user_id-ն և status-ը այստեղ չեն․ դրանք դնում է կոդը, ոչ ֆորման ⭐ */
    protected $fillable = [
        'customer_name', 'email', 'phone', 'delivery', 'region',
        'address', 'payment', 'comment', 'shipping', 'total',
    ];

    protected function casts(): array
    {
        return ['shipping' => 'integer', 'total' => 'integer'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function number(): string
    {
        return 'TM-' . (20000 + $this->id);
    }

    public function statusLabel(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }
}
