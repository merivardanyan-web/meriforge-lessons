<?php
declare(strict_types=1);

// Զամբյուղի էջ — PHP Դաս 7

require_once __DIR__ . '/bootstrap.php';

$items = cartItems();

renderPage('pages/cart', [
    'items'   => $items,
    'summary' => cartSummary($items),
], 'Զամբյուղ — ' . SITE_NAME, 'cart');
