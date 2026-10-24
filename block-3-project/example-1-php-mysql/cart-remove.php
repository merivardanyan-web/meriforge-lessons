<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
requirePost();

if (($_POST['all'] ?? '') === '1') {
    cartClear();
    flash('success', 'Զամբյուղը դատարկվեց');
} elseif (($id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT)) !== false) {
    cartRemove($id);
    flash('success', 'Ապրանքը հեռացվեց');
}

redirect('/cart.php');
