<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

if ($id !== false) {
    listRemove($id);
    flash('success', 'Հանվեց ցուցակից');
}

redirect(safeBack($_POST['back'] ?? 'list.php'));
