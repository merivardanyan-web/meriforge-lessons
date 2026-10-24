<?php
declare(strict_types=1);

// Գրքի էջ՝ book.php?id=2 — PHP Դաս 05
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/data.php';

$book = findBook($books, (int) ($_GET['id'] ?? 0));

if ($book === null) {
    http_response_code(404);
    $content = '<section><div class="row"><div class="col w-100"><p class="empty">Գիրքը չի գտնվել։ <a href="index.php">Ամբողջ դարակը</a></p></div></div></section>';
    $title = 'Չի գտնվել — ' . SITE_NAME;
} else {
    $content = render('pages/book', ['book' => $book]);
    $title = $book['title'] . ' — ' . SITE_NAME;
}

echo render('layout', ['title' => $title, 'active' => 'books', 'content' => $content]);
