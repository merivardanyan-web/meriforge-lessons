<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\CartItem;
use App\Repositories\ProductRepository;

/**
 * Զամբյուղ — PHP Դաս 12
 * &$storage՝ աշխատում ենք ուղիղ $_SESSION['cart']-ի հետ, ոչ պատճենի ⭐
 */
class Cart
{
    private const MAX_QTY = 10;

    public function __construct(private array &$storage) {}

    public function add(int $id, int $qty = 1, int $limit = self::MAX_QTY): void
    {
        if ($qty < 1) {
            return;
        }

        $current = $this->storage[$id] ?? 0;
        $this->storage[$id] = min($current + $qty, self::MAX_QTY, $limit);
    }

    /** Ուղիղ քանակ՝ 0 → հեռացնել — PHP Դաս 12-ի տնային */
    public function setQty(int $id, int $qty, int $limit = self::MAX_QTY): void
    {
        if (!isset($this->storage[$id])) {
            return;
        }

        if ($qty < 1) {
            $this->remove($id);
            return;
        }

        $this->storage[$id] = min($qty, self::MAX_QTY, $limit);
    }

    public function remove(int $id): void
    {
        unset($this->storage[$id]);
    }

    public function clear(): void
    {
        $this->storage = [];
    }

    public function count(): int
    {
        return array_sum($this->storage);
    }

    public function isEmpty(): bool
    {
        return $this->storage === [];
    }

    /** @return int[] */
    public function ids(): array
    {
        return array_map('intval', array_keys($this->storage));
    }

    public function qtyOf(int $id): int
    {
        return $this->storage[$id] ?? 0;
    }

    /**
     * Տողեր՝ ապրանքներով բազայից։ Անջատված ապրանքը հանվում է զամբյուղից
     * @return CartItem[]
     */
    public function items(ProductRepository $products): array
    {
        $found = $products->findMany($this->ids());
        $items = [];

        foreach ($this->storage as $id => $qty) {
            if (!isset($found[$id])) {
                $this->remove((int) $id);
                continue;
            }

            $items[] = new CartItem($found[$id], $qty);
        }

        return $items;
    }
}
