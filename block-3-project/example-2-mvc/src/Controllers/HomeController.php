<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Repositories\ProductRepository;

class HomeController
{
    public function __construct(
        private readonly ProductRepository $products,
    ) {}

    public function index(Request $request): Response
    {
        $sale = ['stock' => 'sale'] + ProductRepository::noFilters();

        return Response::html(page('pages/home', [
            'products' => $this->products->search(ProductRepository::noFilters(), 'newest', 6),
            'sale'     => $this->products->search($sale, 'price_asc', 3),
        ], 'ՏեխնոՄարկետ — տեխնիկա Երևանում'));
    }
}
