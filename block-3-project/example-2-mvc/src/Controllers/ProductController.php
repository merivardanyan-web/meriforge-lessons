<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;

/** Կատալոգ և ապրանքի էջ — PHP Դաս 15։ Ոչ SQL, ոչ HTML․ միայն «ինչ վերցնել» և «որ շաբլոնը» */
class ProductController
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly CategoryRepository $categories,
    ) {}

    public function index(Request $request): Response
    {
        $category = $this->categories->findBySlug($request->query('category'));

        return $this->catalog($request, $category);
    }

    /** /category/laptops — PHP Դաս 15-ի տնային */
    public function category(Request $request, string $slug): Response
    {
        $category = $this->categories->findBySlug($slug)
            ?? throw new NotFoundException('Կատեգորիան չի գտնվել');

        return $this->catalog($request, $category, $category->url());
    }

    /** /search?q=… — PHP Դաս 15-ի տնային */
    public function search(Request $request): Response
    {
        return $this->catalog($request, null, '/search');
    }

    public function show(Request $request, string $slug): Response
    {
        $product = $this->products->findBySlug($slug)
            ?? throw new NotFoundException('Ապրանքը չի գտնվել կամ այլևս չի վաճառվում');

        return Response::html(page('pages/product', [
            'product' => $product,
            'related' => $this->products->related($product),
        ], $product->name . ' — ' . SITE_NAME));
    }

    private function catalog(Request $request, ?Category $category, string $path = '/catalog'): Response
    {
        $filters = [
            'category_id' => $category?->id,
            'q'           => mb_substr($request->query('q'), 0, 100),
            'min'         => $request->queryInt('min'),
            'max'         => $request->queryInt('max'),
            'stock'       => $request->oneOf('stock', array_keys(ProductRepository::STOCK), 'all'),
        ];
        $sort = $request->oneOf('sort', array_keys(ProductRepository::SORTS), 'newest');

        $total = $this->products->count($filters);
        $pages = max(1, (int) ceil($total / ProductRepository::PER_PAGE));
        $page  = min($pages, max(1, $request->int('page', 1)));

        // Ընթացիկ պարամետրերը՝ հղումների համար։ Կատեգորիան ուղու մեջ է, եթե /category/...
        $query = [
            'category' => $path === '/catalog' ? $category?->slug : null,
            'q'        => $filters['q'],
            'min'      => $filters['min'],
            'max'      => $filters['max'],
            'stock'    => $filters['stock'],
            'sort'     => $sort !== 'newest' ? $sort : null,
        ];

        return Response::html(page('pages/catalog', [
            'products' => $this->products->search($filters, $sort, ProductRepository::PER_PAGE, ($page - 1) * ProductRepository::PER_PAGE),
            'category' => $category,
            'filters'  => $filters,
            'sort'     => $sort,
            'total'    => $total,
            'page'     => $page,
            'pages'    => $pages,
            'query'    => $query,
            'path'     => $path,
        ], ($category->name ?? 'Կատալոգ') . ' — ' . SITE_NAME));
    }
}
