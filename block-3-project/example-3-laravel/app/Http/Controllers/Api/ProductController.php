<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

/** Դաս 17-ի ProductApiController-ը՝ Resource-ով — PHP Դաս 20-ի տնային */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min(50, max(1, $request->integer('per_page', 12)));     // ⭐ սահման — Դաս 17
        $q = $request->query('q');

        $products = Product::active()
            ->with('category')
            ->filter(['q' => is_string($q) ? mb_substr(trim($q), 0, 100) : null])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // { data: [...], links: {...}, meta: { current_page, per_page, total, ... } }
        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return new ProductResource($product->load('category'));
    }
}
