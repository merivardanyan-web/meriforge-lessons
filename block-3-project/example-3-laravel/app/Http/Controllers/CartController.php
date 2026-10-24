<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;

/** Մեկ կոնտրոլեր՝ երկու ձևաչափ․ ֆորմա (redirect) և fetch (JSON) — PHP Դաս 17, 19 */
class CartController extends Controller
{
    public function __construct(
        private readonly Cart $cart,          // ⭐ autowiring․ ոչ մի new, ոչ մի bootstrap.php
    ) {}

    public function index()
    {
        $items = $this->cart->items();

        return view('cart.index', [
            'items'   => $items,
            'summary' => $this->cart->summary($items),
        ]);
    }

    public function store(Request $request)
    {
        // abort_if(..., 422)-ի փոխարեն՝ validate․ սխալները գալիս են errors-ով — Դաս 21-ի տնային
        $data = $request->validate([
            'id'  => ['required', 'integer'],
            'qty' => ['nullable', 'integer', 'between:1,10'],
        ]);

        $product = Product::active()->find($data['id']);

        abort_if($product === null, 404, 'Ապրանքը չի գտնվել');

        if (! $product->inStock()) {
            return $this->fail($request, 'Ապրանքը առկա չէ');
        }

        $this->cart->add($product->id, $data['qty'] ?? 1, $product->maxQty());
        $message = 'Ավելացվեց՝ ' . $product->name;

        if ($request->expectsJson()) {
            return response()->json(['data' => ['count' => $this->cart->count(), 'message' => $message]]);
        }

        return back()->with('success', $message);       // Դաս 7-ի flash()
    }

    public function update(Request $request, int $id)
    {
        $current = $this->cart->all()[$id] ?? 0;

        $qty = match ($request->input('action')) {
            'inc'   => $current + 1,
            'dec'   => $current - 1,
            default => $request->integer('qty'),
        };

        $this->cart->setQty($id, $qty, Product::active()->find($id)?->maxQty() ?? 0);

        return redirect()->route('cart.index');
    }

    public function destroy(int $id)
    {
        $this->cart->remove($id);

        return redirect()->route('cart.index')->with('success', 'Ապրանքը հեռացվեց');
    }

    public function clear()
    {
        $this->cart->clear();

        return redirect()->route('cart.index')->with('success', 'Զամբյուղը դատարկվեց');
    }

    private function fail(Request $request, string $message)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $message, 'errors' => ['id' => [$message]]], 422)
            : back()->with('error', $message);
    }
}
