<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Exceptions\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;

/** GET /api/products, /api/products/{slug} — PHP Դաս 17 */
class ProductApiController
{
    private const MAX_PER_PAGE = 50;

    public function __construct(
        private readonly ProductRepository $products,
        private readonly CategoryRepository $categories,
    ) {}

    public function index(Request $request): Response
    {
        $filters = [
            'category_id' => $this->categories->findBySlug($request->query('category'))?->id,
            'q'           => mb_substr($request->query('q'), 0, 100),        // որոնում — Դաս 17-ի խորացված տնային
            'min'         => $request->queryInt('min'),
            'max'         => $request->queryInt('max'),
            'stock'       => $request->oneOf('stock', array_keys(ProductRepository::STOCK), 'all'),
        ];
        $sort    = $request->oneOf('sort', array_keys(ProductRepository::SORTS), 'newest');
        $page    = max(1, $request->int('page', 1));                                    // ?page=abc → 1
        $perPage = min(self::MAX_PER_PAGE, max(1, $request->int('per_page', 12)));      // ?per_page=-5 → 1 ⭐

        $total = $this->products->count($filters);

        return Response::json([
            'data' => array_map(fn(Product $p): array => $p->toArray(), $this->products->search($filters, $sort, $perPage, ($page - 1) * $perPage)),
            'meta' => [
                'page'      => $page,
                'per_page'  => $perPage,
                'total'     => $total,
                'last_page' => max(1, (int) ceil($total / $perPage)),
            ],
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $product = $this->products->findBySlug($slug)
            ?? throw new NotFoundException('Ապրանքը չի գտնվել');

        return Response::json(['data' => $product->toArray()]);
    }
}
