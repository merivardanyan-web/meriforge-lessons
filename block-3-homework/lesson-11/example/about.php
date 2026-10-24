<?php
declare(strict_types=1);

// Երրորդ էջը՝ նույն layout-ով — PHP Դաս 05-ի տնային
require_once __DIR__ . '/bootstrap.php';

echo render('layout', [
    'title'   => 'Ակումբի մասին — ' . SITE_NAME,
    'active'  => 'about',
    'content' => render('pages/about'),
]);
