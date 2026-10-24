<?php
declare(strict_types=1);

// POST → փոփոխություն → redirect — PHP Դաս 07
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$book = $id === false ? null : findBook($books, $id);

if ($book === null) {
    flash('error', 'Գիրքը չի գտնվել');
} elseif (listAdd($book['id'])) {
    flash('success', '«' . $book['title'] . '»-ը ավելացվեց կարդալու ցուցակում');
} else {
    flash('error', 'Գիրքը արդեն ցուցակում է, կամ ցուցակը լի է (' . LIST_MAX . ')');
}

redirect(safeBack($_POST['back'] ?? ''));
