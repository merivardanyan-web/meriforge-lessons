<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\CartItem;
use App\Models\Shippable;

/** Ամփոփում՝ զամբյուղի, պատվերի էջի և API-ի համար՝ նույն հաշվարկը */
class CartSummary
{
    public const FREE_FROM = 50000;

    public readonly int $discount;
    public readonly int $total;

    private function __construct(
        public readonly int $count,
        public readonly int $full,
        public readonly int $subtotal,
        public readonly int $shipping,
    ) {
        $this->discount = $full - $subtotal;
        $this->total = $subtotal + $shipping;
    }

    /** @param CartItem[] $items */
    public static function from(array $items, string $delivery = 'courier'): self
    {
        $count = 0;
        $full = 0;
        $subtotal = 0;

        foreach ($items as $item) {
            $count += $item->qty;
            $full += $item->fullSum();
            $subtotal += $item->sum();
        }

        if ($delivery === 'pickup') {
            $shipping = 0;
        } elseif ($delivery === 'courier' && $subtotal >= self::FREE_FROM) {
            $shipping = 0;                                  // Երևանում՝ անվճար 50 000 ֏-ից
        } else {
            $shipping = self::calculateShipping($items);    // միայն թվային ապրանքներ → 0
        }

        return new self($count, $full, $subtotal, $shipping);
    }

    /**
     * Դաս 13-ի calculateShipping()-ը․ հաշվում է միայն այն, ինչ Shippable է
     * @param CartItem[] $items
     */
    public static function calculateShipping(array $items): int
    {
        $total = 0;

        foreach ($items as $item) {
            if ($item->product instanceof Shippable) {
                $total += $item->product->shippingCost();
            }
        }

        return $total;
    }

    /** API-ի համար — PHP Դաս 17 */
    public function toArray(): array
    {
        return [
            'count'    => $this->count,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'total'    => $this->total,
        ];
    }
}
