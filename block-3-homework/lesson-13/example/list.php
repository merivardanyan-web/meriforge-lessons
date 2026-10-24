<?php
declare(strict_types=1);

// Կարդալու ցուցակի էջ — PHP Դաս 07, 10, 12, 13
require_once __DIR__ . '/bootstrap.php';

$items = listItems();
$pagesLeft = array_reduce($items, fn(int $sum, ListItem $i): int => $sum + $i->book->pagesLeft(), 0);

echo render('layout', [
    'title'   => 'Կարդալու ցուցակ — ' . SITE_NAME,
    'active'  => 'list',
    'content' => render('pages/list', [
        'items'     => $items,
        'pagesLeft' => $pagesLeft,
        'listening' => totalListening(array_map(fn(ListItem $i): Book => $i->book, $items)),
    ]),
]);
