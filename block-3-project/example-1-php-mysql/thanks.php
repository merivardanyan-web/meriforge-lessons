<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$id = filter_var($_GET['order'] ?? '', FILTER_VALIDATE_INT);

// ⭐ Ուրիշի պատվերը ?order=1, 2, 3...-ով տեսնել չի կարելի․ ստուգում ենք սեսիան
if ($id === false || ($_SESSION['last_order'] ?? null) !== $id || ($order = getOrder($id)) === null) {
    notFound('Պատվերը չի գտնվել');
}

renderPage('pages/thanks', ['order' => $order], 'Շնորհակալություն — ' . SITE_NAME);
