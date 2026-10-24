<?php

namespace App\Services;

/**
 * Դաս 12-ի ReadingList-ը՝ Laravel-ի սեսիայով — PHP Դաս 19
 * &$storage-ը այլևս պետք չէ․ session()-ը ինքն է պահում
 */
class ReadingList
{
    public const MAX = 20;
    public const DEFAULT_PER_DAY = 20;

    private const KEY = 'list';

    public function add(int $id): bool
    {
        $items = $this->all();

        if (isset($items[$id]) || count($items) >= self::MAX) {
            return false;
        }

        $items[$id] = self::DEFAULT_PER_DAY;
        session()->put(self::KEY, $items);

        return true;
    }

    public function remove(int $id): void
    {
        session()->forget(self::KEY . '.' . $id);         // 'list.5' — կետով՝ զանգվածի բանալի
    }

    public function has(int $id): bool
    {
        return isset($this->all()[$id]);
    }

    public function count(): int
    {
        return count($this->all());
    }

    /** @return int[] */
    public function ids(): array
    {
        return array_map('intval', array_keys($this->all()));
    }

    public function perDayOf(int $id): int
    {
        return $this->all()[$id] ?? self::DEFAULT_PER_DAY;
    }

    private function all(): array
    {
        return session(self::KEY, []);
    }
}
