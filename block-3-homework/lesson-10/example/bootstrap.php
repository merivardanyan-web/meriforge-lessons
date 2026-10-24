<?php
declare(strict_types=1);

// Մեկ մուտք ամեն էջի համար — PHP Դաս 07, 10
ini_set('display_errors', '1');      // ⚠️ միայն տեղական մեքենայում (Դաս 22-ում՝ 0)
error_reporting(E_ALL);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
]);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';            // Դաս 10․ data.php-ի փոխարեն՝ բազա
require_once __DIR__ . '/books.php';
require_once __DIR__ . '/list-functions.php';
