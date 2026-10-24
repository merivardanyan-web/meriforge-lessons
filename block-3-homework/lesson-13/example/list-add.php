<?php
declare(strict_types=1);

// POST → փոփոխություն → redirect — PHP Դաս 07, 10, 12
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$book = $id === false ? null : getBook($id);

if ($book === null) {
    flash('error', 'Գիրքը չի գտնվել');
} elseif (readingList()->add($book->id)) {
    flash('success', '«' . $book->title . '»-ը ավելացվեց կարդալու ցուցակում');
} else {
    flash('error', 'Գիրքը արդեն ցուցակում է, կամ ցուցակը լի է (' . ReadingList::MAX . ')');
}

redirect(safeBack($_POST['back'] ?? ''));
