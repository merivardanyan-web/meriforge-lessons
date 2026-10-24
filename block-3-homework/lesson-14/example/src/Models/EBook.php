<?php
declare(strict_types=1);

namespace App\Models;

// Էլեկտրոնային գիրք — PHP Դաս 13, տնային 2 (երրորդ տեսակ)
// Նոր դաշտ չունի, ուստի կոնստրուկտոր և fromRow չենք գրում․ ժառանգվում են
// Lendable չէ․ ֆայլը չես «տալիս», պատճենում ես
class EBook extends Book
{
    public static function fromRow(array $row): static
    {
        return new static(
            (int) $row['id'], $row['title'], $row['slug'], $row['author'],
            $row['year'] === null ? null : (int) $row['year'],
            (int) $row['pages'], (int) $row['read_pages'], $row['genre'] ?? 'առանց ժանրի',
            (int) $row['rating'], $row['color'], $row['link'], $row['cover'], $row['created_at'],
        );
    }

    public function formatLabel(): string
    {
        return '💻 Էլեկտրոնային';
    }

    public function minutesLeft(): int
    {
        return $this->pagesLeft() * 2;      // էկրանից՝ նույն արագությամբ
    }
}
