<?php
declare(strict_types=1);

namespace App\Models;

// Ցուցակի տող՝ գիրք + օրական էջեր — PHP Դաս 12, տնային 9 (CartItem-ի նման)
class ListItem
{
    public function __construct(
        public readonly Book $book,
        public readonly int $perDay,
    ) {}

    /** Քանի օրում կավարտեմ՝ կլորացված վերև (5 էջ / 2 = 3 օր, ոչ 2) */
    public function daysLeft(): int
    {
        return (int) (($this->book->pagesLeft() + $this->perDay - 1) / $this->perDay);
    }
}
