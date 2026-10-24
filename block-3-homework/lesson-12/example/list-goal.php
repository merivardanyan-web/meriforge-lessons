<?php
declare(strict_types=1);

// Օրական էջերի նպատակ ցուցակի գրքի համար — PHP Դաս 12, տնային 7
require_once __DIR__ . '/bootstrap.php';
requirePost();

$id    = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$pages = filter_var($_POST['per_day'] ?? '', FILTER_VALIDATE_INT);

if ($id === false || $pages === false || !readingList()->setPerDay($id, $pages)) {
    flash('error', 'Օրական էջերը՝ 1-ից 200');      // վալիդացիան՝ դասի ներսում, ոչ էջում
} else {
    flash('success', 'Նպատակը պահպանվեց՝ օրական ' . $pages . ' էջ');
}

redirect('list.php');
