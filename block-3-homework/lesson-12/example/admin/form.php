<?php
declare(strict_types=1);

// Մեկ ֆայլ երկու գործի համար՝ ստեղծում և խմբագրում — PHP Դաս 11
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../validate.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT) ?: null;
$isEdit = $id !== null;

$errors = [];
$old = ['is_active' => 1, 'read_pages' => 0, 'rating' => 0, 'color' => '#003459', 'genre_id' => ''];
$current = null;

if ($isEdit) {
    $current = getBookForAdmin($id);

    if ($current === null) {
        http_response_code(404);
        exit('Գիրքը չի գտնվել');
    }

    $old = $current;
}

$genres = getGenres();
$genreIds = array_map(fn(Genre $g): int => $g->id, $genres);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateBook($_POST, $genreIds);
    $cover = $current['cover'] ?? null;

    // Շապիկը՝ ոչ պարտադիր։ Ստուգում ենք միայն, եթե ընտրված է (տնային 2, Դաս 07-ի uploadImage)
    $file = $_FILES['cover'] ?? null;
    if ($file !== null && $file['error'] !== UPLOAD_ERR_NO_FILE && $errors === []) {
        [$name, $error] = uploadImage($file, __DIR__ . '/../images/uploads');

        if ($error !== null) {
            $errors['cover'] = $error;
        } else {
            $cover = $name;
        }
    }

    if ($errors === []) {
        $data = bookData($_POST, $cover, $id);

        if ($isEdit) {
            $changed = updateBook($id, $data);
            flash('success', $changed ? 'Պահպանվեց՝ ' . $data['title'] : 'Ոչինչ չփոխվեց');
        } else {
            createBook($data);
            flash('success', 'Ավելացվեց՝ ' . $data['title']);
        }

        redirect('index.php');
    }

    $old = $_POST + ['cover' => $cover];
}

echo render('layout-admin', [
    'title'   => $isEdit ? 'Խմբագրում' : 'Նոր գիրք',
    'active'  => $isEdit ? 'books' : 'new',
    'content' => render('admin/form', ['errors' => $errors, 'old' => $old, 'isEdit' => $isEdit, 'id' => $id, 'genres' => $genres]),
]);
