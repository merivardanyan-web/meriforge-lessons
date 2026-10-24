<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/** Առաջադրանք 2-ի Cart-ը՝ session() helper-ով, առանց &$storage-ի — PHP Դաս 19 */
class Cart
{
    private const MAX_QTY = 10;
    public const FREE_FROM = 50000;

    public function all(): array
    {
        return session('cart', []);
    }

    public function add(int $id, int $qty = 1, int $limit = self::MAX_QTY): void
    {
        if ($qty < 1) {
            return;
        }

        $items = $this->all();
        $items[$id] = min(($items[$id] ?? 0) + $qty, self::MAX_QTY, $limit);

        session(['cart' => $items]);
    }

    public function setQty(int $id, int $qty, int $limit = self::MAX_QTY): void
    {
        $items = $this->all();

        if (! isset($items[$id])) {
            return;
        }

        if ($qty < 1) {
            unset($items[$id]);
        } else {
            $items[$id] = min($qty, self::MAX_QTY, $limit);
        }

        session(['cart' => $items]);
    }

    public function remove(int $id): void
    {
        $items = $this->all();
        unset($items[$id]);

        session(['cart' => $items]);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    public function count(): int
    {
        return array_sum($this->all());
    }

    /**
     * Տողեր՝ ապրանքներով։ Մեկ հարցում (whereIn), ոչ N+1 — Դաս 20
     * @return Collection<int, array{product: Product, qty: int, sum: int}>
     */
    public function items(): Collection
    {
        $cart = $this->all();
        $products = Product::active()->whereIn('id', array_keys($cart))->get()->keyBy('id');

        return collect($cart)
            ->filter(fn (int $qty, int $id) => $products->has($id))
            ->map(fn (int $qty, int $id) => [
                'product' => $products[$id],
                'qty'     => $qty,
                'sum'     => $products[$id]->finalPrice() * $qty,
            ])
            ->values();
    }

    /** Ամփոփում՝ նույն կանոնները, ինչ Առաջադրանք 2-ի CartSummary-ում */
    public function summary(Collection $items, string $delivery = 'courier'): array
    {
        $full = $items->sum(fn (array $i) => $i['product']->price * $i['qty']);
        $subtotal = $items->sum('sum');

        $shipping = match (true) {
            $delivery === 'pickup'                                  => 0,
            $delivery === 'courier' && $subtotal >= self::FREE_FROM => 0,
            default => $items->sum(fn (array $i) => $i['product']->shippingCost()),
        };

        return [
            'count'    => $items->sum('qty'),
            'full'     => $full,
            'discount' => $full - $subtotal,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total'    => $subtotal + $shipping,
        ];
    }
}
