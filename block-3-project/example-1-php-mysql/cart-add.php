<?php
declare(strict_types=1);

// POST → փոփոխություն → redirect — PHP Դաս 6, 7

require_once __DIR__ . '/bootstrap.php';
requirePost();

$id  = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$qty = filter_var($_POST['qty'] ?? '1', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => MAX_QTY]]);

$product = $id === false ? null : (getProductsByIds([$id])[$id] ?? null);

if ($product === null) {
    flash('error', 'Ապրանքը չի գտնվել');
} elseif ($qty === false) {
    flash('error', 'Քանակը՝ 1-ից ' . MAX_QTY);          // curl -d qty=999999 → այստեղ ⭐
} elseif ($product['stock'] < 1) {
    flash('error', 'Ապրանքը սպառված է');
} else {
    cartAdd($id, $qty, $product['stock']);
    flash('success', 'Ավելացվեց զամբյուղ՝ ' . $product['name']);
}

redirect(safeBack($_POST['back'] ?? '/'));
