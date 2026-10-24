<?php
declare(strict_types=1);

// Գլխավոր էջ — կոնտրոլեր (PHP Դաս 05)․ տվյալներ → շաբլոն → layout
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/data.php';

$content = render('pages/home', ['books' => sortBooks($books, 'progress')]);

echo render('layout', [
    'title'   => 'Գրքեր — ' . SITE_NAME,
    'active'  => 'books',
    'content' => $content,
]);
