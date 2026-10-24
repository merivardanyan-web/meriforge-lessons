<?php
declare(strict_types=1);

// Պատվերների ցուցակ՝ JOIN + GROUP BY — PHP Դաս 9

require_once __DIR__ . '/../bootstrap.php';

renderAdmin('admin/orders', ['orders' => getOrders()], 'Պատվերներ', 'orders');
