<?php
declare(strict_types=1);

// Թաքցնել (is_active = 0), ոչ DELETE — PHP Դաս 11
// Իրական DELETE-ը կմերժվեր․ reading_sessions → ON DELETE RESTRICT (Դաս 09, տնային 3)
require_once __DIR__ . '/../bootstrap.php';
requirePost();

$count = archiveBooks((array) ($_POST['ids'] ?? []));

flash($count > 0 ? 'success' : 'error', $count > 0 ? "Թաքցվեց՝ {$count} գիրք" : 'Ոչինչ չնշվեց');
redirect('index.php');
