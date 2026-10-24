<?php
declare(strict_types=1);

// Շապիկի վերբեռնում — PHP Դաս 07, Դաս 10-ից՝ ֆայլի անունը բազայում
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$book = $id === false ? null : getBook($id);

if ($book === null) {
    flash('error', 'Գիրքը չի գտնվել');
    redirect('index.php');
}

[$name, $error] = uploadImage($_FILES['cover'] ?? [], __DIR__ . '/images/uploads');

if ($error !== null) {
    flash('error', $error);
} else {
    saveCover($book['id'], $name);                // UPDATE books SET cover = :cover
    flash('success', 'Շապիկը պահպանվեց');
}

redirect('book.php?id=' . $book['id']);
