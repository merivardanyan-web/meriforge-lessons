<?php
declare(strict_types=1);

// Գլխավոր էջ՝ բազայից, ֆիլտրերով և էջավորմամբ — PHP Դաս 10
require_once __DIR__ . '/bootstrap.php';

$filters = readFilters($_GET);
$sort    = isset(SORT_MAP[$_GET['sort'] ?? '']) ? $_GET['sort'] : 'progress';
$perPage = 6;

$total = countBooks($filters);
$pages = max(1, (int) (($total + $perPage - 1) / $perPage));    // 11 գիրք / 6 → 2 էջ
$page  = min($pages, max(1, (int) ($_GET['page'] ?? 1)));       // ?page=abc → 1, ?page=99 → վերջինը

$books = getBooks($filters, $sort, $perPage, ($page - 1) * $perPage);

echo render('layout', [
    'title'   => 'Գրքեր — ' . SITE_NAME,
    'active'  => 'books',
    'content' => render('pages/home', [
        'books'   => $books,
        'stats'   => getStats(),
        'genres'  => getGenres(),
        'filters' => $filters,
        'sort'    => $sort,
        'total'   => $total,
        'page'    => $page,
        'pages'   => $pages,
    ]),
]);
