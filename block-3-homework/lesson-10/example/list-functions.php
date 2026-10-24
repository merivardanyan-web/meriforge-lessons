<?php
declare(strict_types=1);

// «Կարդալու ցուցակ»՝ սեսիայում — PHP Դաս 07
// Սեսիայում՝ միայն id-ները։ Վերնագիրը, էջերը՝ միշտ բազայից (Դաս 10)

const LIST_MAX = 20;

function readingList(): array
{
    return $_SESSION['list'] ?? [];
}

function listAdd(int $id): bool
{
    $list = readingList();

    if (in_array($id, $list, true) || count($list) >= LIST_MAX) {
        return false;
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

/** Ցուցակի գրքերը՝ ՄԵԿ հարցումով (IN), ոչ թե ամեն id-ի համար առանձին։ Թաքցված գիրքը դուրս է մնում */
function listItems(): array
{
    return getBooksByIds(readingList());
}
