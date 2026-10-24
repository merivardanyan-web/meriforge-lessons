<?php
declare(strict_types=1);

// Ադմին՝ ապրանքների ցուցակ — PHP Դաս 11

require_once __DIR__ . '/../bootstrap.php';

$q = mb_substr(trim((string) ($_GET['q'] ?? '')), 0, 100);

renderAdmin('admin/index', [
    'products' => getAllProducts($q),
    'stats'    => getStats(),
    'q'        => $q,
], 'Ապրանքներ', 'products');
