<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['product_id', 'quantity', 'price'];

    protected function casts(): array
    {
        return ['price' => 'integer', 'quantity' => 'integer'];
    }

    public function product(): BelongsTo
    {
        // ⭐ Ջնջված ապրանքը չպիտի կոտրի հին պատվերը — Դաս 20
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function lineTotal(): int
    {
        return $this->price * $this->quantity;          // գինը գնման պահին, ոչ ընթացիկը — Դաս 9
    }
}
