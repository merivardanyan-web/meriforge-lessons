<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

/** Առաջադրանք 2-ի ProductController-ը — PHP Դաս 18–20 */
class ProductController extends Controller
{
    /** Սպիտակ ցուցակը մնում է․ Eloquent-ը նույնպես չի պարամետրացնում սյունակի անունը ⭐ Դաս 18 */
    public const SORTS = [
        'newest'     => ['Նորերը', 'created_at', 'desc'],
        'price_asc'  => ['Գինը՝ աճման կարգով', 'final_price', 'asc'],
        'price_desc' => ['Գինը՝ նվազման կարգով', 'final_price', 'desc'],
        'name'       => ['Անունով', 'name', 'asc'],
    ];

    public const STOCK = ['all' => 'Բոլորը', 'in' => 'Միայն առկաները', 'sale' => 'Միայն զեղչվածները'];

    public function home()
    {
        return view('products.home', [
            'products' => Product::active()->latest()->take(6)->get(),
            'sale'     => Product::active()->where('discount', '>', 0)->orderByRaw('ROUND(price * (100 - discount) / 100)')->take(3)->get(),
        ]);
    }

    public function index(Request $request)
    {
        $category = Category::where('slug', $this->text($request, 'category'))->first();

        return $this->catalog($request, $category);
    }

    public function category(Request $request, Category $category)
    {
        return $this->catalog($request, $category);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);          // ⭐ անջատվածը՝ 404, Դաս 20

        $product->load('category');

        $related = Product::active()
            ->whereKeyNot($product->id)
            ->orderByRaw('category_id = ? desc', [$product->category_id])
            ->latest()
            ->take(3)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    private function catalog(Request $request, ?Category $category)
    {
        // URL-ի պարամետրերը՝ ստուգված։ Անհայտ արժեքը դառնում է լռելյայն, ոչ սխալ — Դաս 10, 18
        $filters = [
            'category_id' => $category?->id,
            'q'           => mb_substr(trim($this->text($request, 'q')), 0, 100),
            'min'         => $this->number($request, 'min'),
            'max'         => $this->number($request, 'max'),
            'stock'       => $this->oneOf($request, 'stock', self::STOCK, 'all'),
        ];

        $sort = $this->oneOf($request, 'sort', self::SORTS, 'newest');
        [, $column, $direction] = self::SORTS[$sort];

        $products = Product::active()
            ->select('*')
            ->selectRaw('ROUND(price * (100 - discount) / 100) AS final_price')
            ->filter($filters)
            ->orderBy($column, $direction)
            ->orderBy('id')
            ->paginate(6)
            ->withQueryString();                         // ?sort=-ը մնում է երկրորդ էջում — Դաս 18

        return view('products.index', [
            'products'   => $products,
            'category'   => $category,
            'categories' => Category::orderBy('id')->get(),
            'filters'    => $filters,
            'sort'       => $sort,
        ]);
    }

    // ---------- URL-ի պարամետրեր — Առաջադրանք 2-ի Request::query(), queryInt(), oneOf() ----------

    private function text(Request $request, string $key): string
    {
        $value = $request->query($key);

        return is_string($value) ? $value : '';            // ?q[]=x-ը զանգված է, ոչ տող
    }

    private function number(Request $request, string $key): ?int
    {
        $value = filter_var($this->text($request, $key), FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

        return $value === false ? null : $value;
    }

    private function oneOf(Request $request, string $key, array $allowed, string $default): string
    {
        $value = $this->text($request, $key);

        return array_key_exists($value, $allowed) ? $value : $default;
    }
}
