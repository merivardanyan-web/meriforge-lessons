<?php
declare(strict_types=1);

// «Կարդալու ցուցակ»՝ սեսիայում — PHP Դաս 07 (ՏեխնոՄարկետի զամբյուղի նույն գաղափարը)
// Սեսիայում՝ միայն id-ները։ Վերնագիրը, էջերը՝ միշտ $books-ից

const LIST_MAX = 20;

function readingList(): array
{
    return $_SESSION['list'] ?? [];
}

function listAdd(int $id): bool
{
    $list = readingList();

    if (in_array($id, $list, true) || count($list) >= LIST_MAX) {
        return false;                      // արդեն կա կամ ցուցակը լի է
    }

    $_SESSION['list'][] = $id;

    return true;
}

function listRemove(int $id): void
{
    $_SESSION['list'] = array_values(array_filter(readingList(), fn(int $x): bool => $x !== $id));
}

function listCount(): int
{
    return count(readingList());
}

function inList(int $id): bool
{
    return in_array($id, readingList(), true);
}

/** Ցուցակի գրքերը՝ $books-ից։ Ջնջված գիրքը դուրս է մնում */
function listItems(array $books): array
{
    $items = [];

    foreach (readingList() as $id) {
        $book = findBook($books, $id);

        if ($book !== null) {
            $items[] = $book;
        }
    }

    return $items;
}
