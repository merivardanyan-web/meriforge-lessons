<?php
declare(strict_types=1);

// Մեկ ֆայլ երկու գործի համար՝ ստեղծում և խմբագրում — PHP Դաս 11
require_once __DIR__ . '/../bootstrap.php';

use App\Services\Validator;

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT) ?: null;
$isEdit = $id !== null;

$errors = [];
$old = ['is_active' => 1, 'read_pages' => 0, 'rating' => 0, 'color' => '#003459', 'genre_id' => '', 'format' => 'paper'];
$current = null;

if ($isEdit) {
    $current = $books->findForAdmin($id);

    if ($current === null) {
        http_response_code(404);
        exit('Գիրքը չի գտնվել');
    }

    $old = $current;
}

$genreList = $genres->all();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = Validator::book($_POST, $genres->ids());
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
        $data = $books->dataFrom($_POST, $cover, $id);

        if ($isEdit) {
            $changed = $books->update($id, $data);
            flash('success', $changed ? 'Պահպանվեց՝ ' . $data['title'] : 'Ոչինչ չփոխվեց');
        } else {
            $books->create($data);
            flash('success', 'Ավելացվեց՝ ' . $data['title']);
        }

        redirect('index.php');
    }

    $old = $_POST + ['cover' => $cover];
}

echo render('layout-admin', [
    'title'   => $isEdit ? 'Խմբագրում' : 'Նոր գիրք',
    'active'  => $isEdit ? 'books' : 'new',
    'content' => render('admin/form', ['errors' => $errors, 'old' => $old, 'isEdit' => $isEdit, 'id' => $id, 'genres' => $genreList]),
]);
