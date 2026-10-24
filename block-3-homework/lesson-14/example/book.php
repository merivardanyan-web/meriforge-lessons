<?php
declare(strict_types=1);

// Գրքի էջ՝ book.php?id=2 — PHP Դաս 05, 10, 11, 14
require_once __DIR__ . '/bootstrap.php';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
$book = $id === false ? null : $books->find($id);          // prepared statement․ ?id=1 OR 1=1 → false

if ($book === null) {
    http_response_code(404);
    echo render('layout', [
        'title'   => 'Չի գտնվել — ' . SITE_NAME,
        'content' => '<section><div class="row"><div class="col w-100"><p class="empty">Գիրքը չի գտնվել։ <a href="index.php">Ամբողջ դարակը</a></p></div></div></section>',
    ]);
    exit;
}

echo render('layout', [
    'title'   => $book->title . ' — ' . SITE_NAME,
    'active'  => 'books',
    'content' => render('pages/book', ['book' => $book, 'sessions' => $sessions->forBook($book->id)]),
]);
