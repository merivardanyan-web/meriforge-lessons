<?php
// Պատճենիր config.php անունով և լրացրու քո տվյալները — PHP Դաս 10
// config.php-ը .gitignore-ում է․ գաղտնաբառը GitHub չի գնում
return [
    'db' => [
        'host'     => '127.0.0.1',
        'name'     => 'ani_books',
        'user'     => 'root',
        'password' => '',            // Laragon-ում՝ դատարկ
        'charset'  => 'utf8mb4',
    ],
];
