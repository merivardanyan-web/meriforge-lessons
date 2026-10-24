<?php
declare(strict_types=1);

// Մեկ մուտք ամեն էջի համար — PHP Դաս 07
ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start([
    'cookie_httponly' => true,    // JS-ը չի կարդում սեսիայի cookie-ն
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
]);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/list-functions.php';
require_once __DIR__ . '/data.php';
