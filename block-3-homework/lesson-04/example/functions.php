<?php
declare(strict_types=1);

// Գրքերի ֆունկցիաները — PHP Դաս 03, ուղղված Դաս 04-ում (տես review.md)
// Ամեն ֆունկցիա ստանում է այն, ինչ պետք է, պարամետրով․ ոչ մի global

function formatPages(int $pages): string
{
    return number_format($pages, 0, ".", " ") . " էջ";
}

function bookStatus(array $book): string
{
    if ($book['read'] <= 0) {          // Դաս 04․ բացասականը նույնպես «դեռ չեմ սկսել»
        return 'planned';
    }

    return $book['read'] >= $book['pages'] ? 'done' : 'reading';
}

function statusLabel(string $status): string
{
    return match ($status) {
        'done'    => 'Կարդացել եմ',
        'reading' => 'Կարդում եմ',
        'planned' => 'Կարդալու եմ',
        default   => 'Անհայտ',
    };
}

/**
 * Դաս 04-ի վերանայումից հետո․
 *  - pages = 0 → DivisionByZeroError → վերադարձնում ենք 0
 *  - read > pages (տառասխալ ադմինում) → 120% → սահմանափակում ենք 100-ով
 *  - read < 0 → -10% → 0
 */
function progress(array $book): int
{
    if ($book['pages'] <= 0) {
        return 0;
    }

    $percent = (int) ($book['read'] * 100 / $book['pages']);

    return max(0, min(100, $percent));
}

function filterByStatus(array $books, string $status): array
{
    $result = array_filter($books, fn(array $b): bool => bookStatus($b) === $status);

    return array_values($result);        // ⭐ վերահամարակալում՝ 0, 1, 2...
}

function filterByGenre(array $books, string $genre): array
{
    return array_values(array_filter($books, fn(array $b): bool => $b['genre'] === $genre));
}

function findBook(array $books, int $id): ?array
{
    foreach ($books as $book) {
        if ($book['id'] === $id) {
            return $book;
        }
    }

    return null;                          // չգտնվեց — ոչ false, ոչ []
}

function sortBooks(array $books, string $by = 'title'): array
{
    match ($by) {
        'title'    => usort($books, fn($a, $b) => strcmp($a['title'], $b['title'])),
        'year'     => usort($books, fn($a, $b) => ($a['year'] ?? 0) <=> ($b['year'] ?? 0)),
        'pages'    => usort($books, fn($a, $b) => $b['pages'] <=> $a['pages']),
        'progress' => usort($books, fn($a, $b) => progress($b) <=> progress($a)),
        default    => null,
    };

    return $books;
}

/** Ընդամենը կարդացած էջեր */
function totalRead(array $books): int
{
    return array_reduce($books, fn(int $sum, array $b): int => $sum + $b['read'], 0);
}

/** ['վեպ' => [գիրք, գիրք], 'հեքիաթ' => [գիրք]] — Դաս 03-ի տնային */
function groupByGenre(array $books): array
{
    $groups = [];

    foreach ($books as $book) {
        $groups[$book['genre']][] = $book;
    }

    return $groups;
}

function averagePages(array $books): int
{
    if ($books === []) {
        return 0;
    }

    $total = array_reduce($books, fn(int $sum, array $b): int => $sum + $b['pages'], 0);

    return (int) ($total / count($books));
}

function longestBook(array $books): ?array
{
    $sorted = sortBooks($books, 'pages');

    return $sorted[0] ?? null;
}

function shortestBook(array $books): ?array
{
    $sorted = sortBooks($books, 'pages');

    return $sorted[count($sorted) - 1] ?? null;
}
