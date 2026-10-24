<?php
declare(strict_types=1);

// «Կարդացի այսօր» — PHP Դաս 11․ տրանզակցիա՝ logReading()
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/validate.php';
requirePost();

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$book = $id === false ? null : getBook($id);

if ($book === null) {
    flash('error', 'Գիրքը չի գտնվել');
    redirect('index.php');
}

$errors = validateReading($_POST, $book);

if ($errors !== []) {
    flash('error', implode(' · ', $errors));
    redirect('book.php?id=' . $book->id);
}

try {
    logReading($book->id, (int) $_POST['pages'], $_POST['read_on'], trim($_POST['note'] ?? '') ?: null);
    flash('success', '+' . (int) $_POST['pages'] . ' էջ «' . $book->title . '»-ից');
} catch (RuntimeException $e) {
    flash('error', $e->getMessage());       // մեր հաղորդագրությունը, ոչ PDO-ինը
}

redirect('book.php?id=' . $book->id);
