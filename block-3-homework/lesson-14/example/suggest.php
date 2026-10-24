<?php
declare(strict_types=1);

// «Առաջարկիր գիրք» — PHP Դաս 06, flash()-ով՝ Դաս 07
require_once __DIR__ . '/bootstrap.php';

use App\Services\Validator;

$errors = [];
$old    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = Validator::suggestion($_POST);

    if ($errors === []) {
        // Այստեղ կուղարկեինք նամակ Անիին կամ կպահեինք բազայում (Դաս 10–11)
        flash('success', 'Շնորհակալություն։ Անին կկարդա առաջարկդ այս շաբաթ։');   // Դաս 07-ի flash()
        redirect('suggest.php');
    }

    $old = $_POST;                 // սխալ կա՝ պահում ենք լրացրածը
}

echo render('layout', [
    'title'   => 'Առաջարկիր գիրք — ' . SITE_NAME,
    'active'  => 'suggest',
    'content' => render('pages/suggest', [
        'errors'  => $errors,
        'old'     => $old,
        'genres'  => Validator::GENRES,
    ]),
]);
