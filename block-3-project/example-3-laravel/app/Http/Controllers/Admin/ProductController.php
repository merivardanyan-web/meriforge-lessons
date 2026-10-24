<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/** Resource controller — PHP Դաս 19, 20, 21 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = mb_substr(trim((string) $request->string('q')), 0, 100);

        // Ադմինը տեսնում է նաև անջատվածները, ոչ ջնջվածները — Դաս 11, 20
        $products = Product::with('category')
            ->when($q !== '', fn ($query) => $query->where('name', 'like', '%' . addcslashes($q, '%_\\') . '%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'q'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => null, 'categories' => Category::orderBy('id')->get()]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->safe()->except('image');                 // ⭐ միայն rules()-ում թվարկվածները
        $data['slug'] = Product::uniqueSlugFor($data['name']);
        $data['image'] = $this->storeImage($request);

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Ապրանքն ավելացվեց՝ ' . $data['name']);
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('id')->get()]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->safe()->except('image');
        $data['slug'] = Product::uniqueSlugFor($data['name'], $product->id);
        $data['image'] = $this->storeImage($request) ?? $product->image;

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Ապրանքը թարմացվեց՝ ' . $data['name']);
    }

    public function destroy(Product $product)
    {
        // ⭐ Ուղու can:manage-products-ը թույլ է տալիս մենեջերին։ Ջնջելը՝ միայն ադմինին
        Gate::authorize('delete-products');

        $product->delete();                                          // փափուկ — Դաս 20

        return redirect()->route('admin.products.index')->with('success', 'Ապրանքը հեռացվեց՝ ' . $product->name);
    }

    /** «Ջնջվածներ» — Դաս 20-ի տնային */
    public function trashed()
    {
        return view('admin.products.trashed', [
            'products' => Product::onlyTrashed()->with('category')->latest('deleted_at')->get(),
        ]);
    }

    public function restore(Product $product)
    {
        Gate::authorize('delete-products');

        $product->restore();

        return redirect()->route('admin.products.trashed')->with('success', 'Վերականգնվեց՝ ' . $product->name);
    }

    /** Դաս 7-ի uploadImage()-ի վեց քայլը՝ մեկ տողով (ստուգումը՝ ProductRequest-ում) — Դաս 21-ի տնային */
    private function storeImage(ProductRequest $request): ?string
    {
        return $request->file('image')?->store('products', 'public');     // php artisan storage:link
    }
}
