<?php
declare(strict_types=1);

// Գլխավոր էջ — PHP Դաս 05, 07
require_once __DIR__ . '/bootstrap.php';

echo render('layout', [
    'title'   => 'Գրքեր — ' . SITE_NAME,
    'active'  => 'books',
    'content' => render('pages/home', ['books' => sortBooks($books, 'progress')]),
]);
