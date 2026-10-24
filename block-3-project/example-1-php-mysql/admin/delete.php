<?php
declare(strict_types=1);

// Փափուկ ջնջում (is_active = 0) — PHP Դաս 11
// Իրական DELETE-ը կկոտրեր հին պատվերները (order_items → ON DELETE RESTRICT, Դաս 9)

require_once __DIR__ . '/../bootstrap.php';
requirePost();

$ids = (array) ($_POST['ids'] ?? []);
$count = archiveProducts($ids);

flash($count > 0 ? 'success' : 'error', $count > 0 ? "Արխիվացվեց՝ {$count} ապրանք" : 'Ոչինչ չնշվեց');
redirect('/admin/index.php');
