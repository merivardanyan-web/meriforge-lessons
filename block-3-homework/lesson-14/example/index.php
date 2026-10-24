<?php
declare(strict_types=1);

// Գլխավոր էջ — PHP Դաս 10, Դաս 14-ից՝ Repository-ով
require_once __DIR__ . '/bootstrap.php';

use App\Repositories\BookRepository;

$filters = BookRepository::filtersFrom($_GET);
$sort    = isset(BookRepository::SORTS[$_GET['sort'] ?? '']) ? $_GET['sort'] : 'progress';
$perPage = BookRepository::PER_PAGE;

$total = $books->count($filters);
$pages = max(1, (int) (($total + $perPage - 1) / $perPage));
$page  = min($pages, max(1, (int) ($_GET['page'] ?? 1)));

echo render('layout', [
    'title'   => 'Գրքեր — ' . SITE_NAME,
    'active'  => 'books',
    'content' => render('pages/home', [
        'books'   => $books->search($filters, $sort, $perPage, ($page - 1) * $perPage),
        'stats'   => $books->stats(),
        'genres'  => $genres->all(),
        'sorts'   => BookRepository::SORTS,
        'filters' => $filters,
        'sort'    => $sort,
        'total'   => $total,
        'page'    => $page,
        'pages'   => $pages,
    ]),
]);
