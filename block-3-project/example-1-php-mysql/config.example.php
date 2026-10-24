<?php
declare(strict_types=1);

// Պատճենիր որպես config.php և լրացրու։ config.php-ն .gitignore-ում է — PHP Դաս 10
return [
    'debug' => true,                 // ⚠️ hosting-ում՝ false

    'db' => [
        'host'     => '127.0.0.1',
        'name'     => 'technomarket',
        'user'     => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
    ],
];
