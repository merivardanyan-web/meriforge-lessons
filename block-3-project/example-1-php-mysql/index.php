<?php
declare(strict_types=1);

// Գլխավոր էջ — կոնտրոլերի դերում (PHP Դաս 5)․ տվյալներ → շաբլոն → layout

require_once __DIR__ . '/bootstrap.php';

$noFilters = readFilters([]);

renderPage('pages/home', [
    'products' => getProducts($noFilters, 'newest', 6),
    'sale'     => getProducts(readFilters(['stock' => 'sale']), 'price_asc', 3),
], 'ՏեխնոՄարկետ — տեխնիկա Երևանում', 'home');
