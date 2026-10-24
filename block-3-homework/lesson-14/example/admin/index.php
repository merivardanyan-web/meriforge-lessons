<?php
declare(strict_types=1);

// Ադմին՝ գրքերի ցուցակ — PHP Դաս 11
// ⚠️ Դեռ բաց է բոլորի համար։ Դաս 16-ում կփակենք մուտքով և դերով
require_once __DIR__ . '/../bootstrap.php';

$q = trim((string) ($_GET['q'] ?? ''));

echo render('layout-admin', [
    'title'   => 'Գրքեր',
    'active'  => 'books',
    'content' => render('admin/index', ['books' => $books->adminList($q), 'q' => $q]),
]);
