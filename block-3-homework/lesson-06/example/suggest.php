<?php
declare(strict_types=1);

// «Առաջարկիր գիրք» — PHP Դաս 06․ ֆորմա, վալիդացիա, POST/Redirect/GET
ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/validate.php';

$errors  = [];
$old     = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = validateSuggestion($_POST);

    if ($errors === []) {
        // Այստեղ կուղարկեինք նամակ Անիին կամ կպահեինք բազայում (Դաս 10–11)
        $_SESSION['flash'] = 'sent';

        header('Location: suggest.php', true, 303);
        exit;                      // ⭐ առանց սրա կոդը կշարունակեր կատարվել
    }

    $old = $_POST;                 // սխալ կա՝ պահում ենք լրացրածը
}

if (isset($_SESSION['flash'])) {
    $success = $_SESSION['flash'] === 'sent';
    unset($_SESSION['flash']);     // ցույց տալիս՝ ջնջում ենք
}

echo render('layout', [
    'title'   => 'Առաջարկիր գիրք — ' . SITE_NAME,
    'active'  => 'suggest',
    'content' => render('pages/suggest', [
        'errors'  => $errors,
        'old'     => $old,
        'success' => $success,
        'genres'  => GENRES,
    ]),
]);
