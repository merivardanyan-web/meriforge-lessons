<?php
declare(strict_types=1);

// Մեկ մուտք ամեն էջի համար — PHP Դաս 07, 10, 12
ini_set('display_errors', '1');      // ⚠️ միայն տեղական մեքենայում (Դաս 22-ում՝ 0)
error_reporting(E_ALL);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
]);

// Դաս 12․ դասերը (Դաս 14-ում Composer-ը կբեռնի ինքը)
require_once __DIR__ . '/classes/Book.php';
require_once __DIR__ . '/classes/Genre.php';
require_once __DIR__ . '/classes/ReadingList.php';
require_once __DIR__ . '/classes/ListItem.php';

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';            // Դաս 10․ data.php-ի փոխարեն՝ բազա
require_once __DIR__ . '/books.php';

// Կարդալու ցուցակը՝ մեկ օբյեկտ ամբողջ հարցման համար (Դաս 07-ի list-functions.php-ի փոխարեն)
$_SESSION['list'] ??= [];

function readingList(): ReadingList
{
    static $list = null;

    return $list ??= new ReadingList($_SESSION['list']);
}

/** @return ListItem[] */
function listItems(): array
{
    $list = readingList();

    return array_map(
        fn(Book $book): ListItem => new ListItem($book, $list->perDayOf($book->id)),
        getBooksByIds($list->ids())
    );
}
