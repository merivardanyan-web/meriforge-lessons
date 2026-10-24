<?php
declare(strict_types=1);

namespace App\Models;

/** Զամբյուղի տող՝ ապրանք + քանակ — PHP Դաս 12-ի տնային */
class CartItem
{
    public function __construct(
        public readonly Product $product,
        public readonly int $qty,
    ) {}

    public function sum(): int
    {
        return $this->product->finalPrice() * $this->qty;
    }

    public function fullSum(): int
    {
        return $this->product->price * $this->qty;
    }
}
