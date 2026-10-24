<?php
declare(strict_types=1);

// Թեմա cookie-ով — PHP Դաս 07-ի տնային
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = isDark() ? 'light' : 'dark';         // միայն երկու արժեք՝ սպիտակ ցուցակ

    setcookie('theme', $theme, [
        'expires'  => time() + 60 * 60 * 24 * 365,
        'path'     => '/',
        'httponly' => false,                       // թեման JS-ին նույնպես կարող է պետք լինել
        'samesite' => 'Lax',
    ]);
}

redirect(safeBack($_POST['back'] ?? ''));
