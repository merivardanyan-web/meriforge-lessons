<?php
declare(strict_types=1);

// Մեկ ֆայլ երկու գործի համար՝ ստեղծում և խմբագրում — PHP Դաս 11

require_once __DIR__ . '/../bootstrap.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT) ?: null;
$isEdit = $id !== null;

$errors = [];
$old = ['is_active' => 1, 'discount' => 0, 'stock' => 0];
$current = null;

if ($isEdit) {
    $current = getProductForAdmin($id);

    if ($current === null) {
        http_response_code(404);
        exit('Ապրանքը չի գտնվել');
    }

    $old = $current;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateProduct($_POST);
    $image = $current['image'] ?? null;

    // Նկարը՝ ոչ պարտադիր։ Ստուգում ենք միայն, եթե ընտրված է — PHP Դաս 7
    $file = $_FILES['image'] ?? null;
    if ($file !== null && $file['error'] !== UPLOAD_ERR_NO_FILE && $errors === []) {
        [$name, $error] = uploadImage($file, __DIR__ . '/../images/uploads');

        if ($error !== null) {
            $errors['image'] = $error;
        } else {
            $image = 'uploads/' . $name;
        }
    }

    if ($errors === []) {
        $data = productData($_POST, $image, $id);

        if ($isEdit) {
            updateProduct($id, $data);
            flash('success', 'Ապրանքը թարմացվեց՝ ' . $data['name']);
        } else {
            createProduct($data);
            flash('success', 'Ապրանքն ավելացվեց՝ ' . $data['name']);
        }

        redirect('/admin/index.php');
    }

    $old = $_POST + ['image' => $current['image'] ?? null];
}

renderAdmin('admin/form', [
    'errors'     => $errors,
    'old'        => $old,
    'isEdit'     => $isEdit,
    'id'         => $id,
    'categories' => getCategories(),
], $isEdit ? 'Խմբագրում' : 'Նոր ապրանք', 'products');
