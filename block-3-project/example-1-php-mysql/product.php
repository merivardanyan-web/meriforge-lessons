<?php
declare(strict_types=1);

// Ապրանքի էջ՝ /product.php?slug=noutbuq-pro-14
// Դաս 15-ում հասցեն կդառնա /product/noutbuq-pro-14

require_once __DIR__ . '/bootstrap.php';

$product = getProductBySlug((string) ($_GET['slug'] ?? ''));

if ($product === null) {
    notFound('Ապրանքը չի գտնվել կամ այլևս չի վաճառվում');    // 404, ոչ դատարկ էջ ⭐
}

renderPage('pages/product', [
    'product' => $product,
    'related' => getRelated($product),
    'inCart'  => cart()[$product['id']] ?? 0,
], $product['name'] . ' — ' . SITE_NAME, 'catalog');
