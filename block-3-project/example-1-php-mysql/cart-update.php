<?php
declare(strict_types=1);

// Քանակի փոփոխություն՝ +/− կոճակներ կամ դաշտ — PHP Դաս 7-ի տնային

require_once __DIR__ . '/bootstrap.php';
requirePost();

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

if ($id !== false) {
    $qty = match ($_POST['action'] ?? '') {
        'inc'   => (cart()[$id] ?? 0) + 1,
        'dec'   => (cart()[$id] ?? 0) - 1,
        default => (int) ($_POST['qty'] ?? 0),
    };
    $stock = getProductsByIds([$id])[$id]['stock'] ?? 0;
    cartSet($id, $qty, $stock);       // 0 → հեռացվում է, 50 → MAX_QTY կամ պահեստի քանակը
}

redirect('/cart.php');
