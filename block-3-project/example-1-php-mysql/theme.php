<?php
declare(strict_types=1);

// Թեմայի պահպանում cookie-ով — PHP Դաս 7-ի տնային
// httponly՝ false, որովհետև ապագայում JS-ը նույնպես կարող է կարդալ այն

require_once __DIR__ . '/bootstrap.php';
requirePost();

$theme = ($_POST['theme'] ?? '') === 'dark' ? 'dark' : 'light';   // միայն երկու արժեք

setcookie('theme', $theme, [
    'expires'  => time() + 60 * 60 * 24 * 365,
    'path'     => '/',
    'httponly' => false,
    'samesite' => 'Lax',
]);

redirect(safeBack($_POST['back'] ?? '/'));
