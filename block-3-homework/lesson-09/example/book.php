<?php
declare(strict_types=1);

// Գրքի էջ՝ book.php?id=2 — PHP Դաս 05, 07
require_once __DIR__ . '/bootstrap.php';

$book = findBook($books, (int) ($_GET['id'] ?? 0));

if ($book === null) {
    http_response_code(404);
    echo render('layout', [
        'title'   => 'Չի գտնվել — ' . SITE_NAME,
        'content' => '<section><div class="row"><div class="col w-100"><p class="empty">Գիրքը չի գտնվել։ <a href="index.php">Ամբողջ դարակը</a></p></div></div></section>',
    ]);
    exit;
}

echo render('layout', [
    'title'   => $book['title'] . ' — ' . SITE_NAME,
    'active'  => 'books',
    'content' => render('pages/book', ['book' => $book]),
]);
