<?php
declare(strict_types=1);

// Կատալոգ՝ ֆիլտրեր, որոնում, դասավորում, էջավորում — PHP Դաս 10
// Ամբողջ վիճակը URL-ում է․ հղումը կարելի է կիսել, F5-ը ոչինչ չի կորցնում

require_once __DIR__ . '/bootstrap.php';

$filters = readFilters($_GET);
$sort    = array_key_exists($_GET['sort'] ?? '', SORT_MAP) ? $_GET['sort'] : 'newest';

$total = countProducts($filters);
$pages = max(1, (int) ceil($total / PER_PAGE));
$page  = min($pages, max(1, (int) ($_GET['page'] ?? 1)));    // ?page=999 → վերջին էջը

$products = getProducts($filters, $sort, PER_PAGE, ($page - 1) * PER_PAGE);

// Ընթացիկ պարամետրերը՝ էջավորման և դասավորման հղումների համար
$query = [
    'category' => $filters['category'],
    'q'        => $filters['q'],
    'min'      => $filters['min'],
    'max'      => $filters['max'],
    'stock'    => $filters['stock'],
    'sort'     => $sort !== 'newest' ? $sort : null,
];

$category = $filters['category'] ? getCategoryBySlug($filters['category']) : null;

renderPage('pages/catalog', [
    'products'   => $products,
    'categories' => getCategories(),
    'category'   => $category,
    'filters'    => $filters,
    'sort'       => $sort,
    'total'      => $total,
    'page'       => $page,
    'pages'      => $pages,
    'query'      => $query,
    'from'       => $total === 0 ? 0 : ($page - 1) * PER_PAGE + 1,
    'to'         => min($total, $page * PER_PAGE),
], ($category['name'] ?? 'Կատալոգ') . ' — ' . SITE_NAME, 'catalog');
