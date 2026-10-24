<?php
declare(strict_types=1);

// Մեկ մուտք ամեն էջի համար — PHP Դաս 7
// Ամեն կոնտրոլեր (index.php, cart.php, admin/...) սկսում է այս ֆայլով

if (!file_exists(__DIR__ . '/config.php')) {
    exit('Ստեղծիր config.php՝ config.example.php-ի պատճենով (README.md)');
}

require_once __DIR__ . '/functions.php';

$debug = config('debug') === true;
ini_set('display_errors', $debug ? '1' : '0');
error_reporting(E_ALL);

session_start([
    'cookie_httponly' => true,     // JS-ը չի կարդում
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
]);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/validate.php';
require_once __DIR__ . '/products.php';
require_once __DIR__ . '/cart-functions.php';
require_once __DIR__ . '/orders.php';
