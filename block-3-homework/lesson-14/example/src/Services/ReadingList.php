<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Book;
use App\Models\ListItem;
use App\Repositories\BookRepository;

// Կարդալու ցուցակ՝ դաս — PHP Դաս 12
// Պահում է [id => օրական էջ]։ Աշխատում է ՀՂՈՒՄՈՎ սեսիայի զանգվածի վրա (&$storage)․
// առանց &-ի կփոխեր պատճենը, և $_SESSION-ը դատարկ կմնար (տնային 6)

class ReadingList
{
    public const MAX = 20;
    public const DEFAULT_PER_DAY = 20;

    public function __construct(private array &$storage) {}

    public function add(int $id): bool
    {
        if ($this->has($id) || $this->count() >= self::MAX) {
            return false;
        }

        $this->storage[$id] = self::DEFAULT_PER_DAY;

        return true;
    }

    public function remove(int $id): void
    {
        unset($this->storage[$id]);
    }

    public function has(int $id): bool
    {
        return isset($this->storage[$id]);
    }

    public function count(): int
    {
        return count($this->storage);
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

    public function perDayOf(int $id): int
    {
        return $this->storage[$id] ?? self::DEFAULT_PER_DAY;
    }

    /** Տնային 7 (Cart::setQty-ի նման)․ օրական 1–200 էջ, միայն ցուցակում եղած գրքի համար */
    public function setPerDay(int $id, int $pages): bool
    {
        if (!$this->has($id) || $pages < 1 || $pages > 200) {
            return false;
        }

        $this->storage[$id] = $pages;

        return true;
    }

    /** Ցուցակի տողերը՝ գիրք + նպատակ։ Գրքերը՝ Repository-ից, մեկ հարցումով
     *  @return ListItem[] */
    public function items(BookRepository $books): array
    {
        return array_map(
            fn(Book $book): ListItem => new ListItem($book, $this->perDayOf($book->id)),
            $books->findMany($this->ids())
        );
    }
}
