<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly Cart $cart,
    ) {}

    public function create(Request $request)
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Զամբյուղը դատարկ է');
        }

        return view('checkout.create', [
            'items'   => $items,
            'summary' => $this->cart->summary($items, old('delivery', 'courier')),
            'user'    => $request->user(),
        ]);
    }

    public function store(CheckoutRequest $request)
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $data = $request->validated();
        $summary = $this->cart->summary($items, $data['delivery']);

        // Առաջադրանք 1-ի beginTransaction/commit/rollBack-ը՝ մեկ ֆունկցիայով — PHP Դաս 11
        $order = DB::transaction(function () use ($data, $items, $summary, $request) {
            $order = new Order(collect($data)->except('agree')->all() + [
                'shipping' => $summary['shipping'],
                'total'    => $summary['total'],
            ]);
            $order->user_id = $request->user()?->id;      // ⭐ ոչ ֆորմայից
            $order->save();

            foreach ($items as $item) {
                $product = $item['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $item['qty'],
                    'price'      => $product->finalPrice(),
                ]);

                if ($product->isDigital()) {
                    continue;
                }

                // UPDATE ... WHERE stock >= qty — վերջին ապրանքը երկու հոգու չի վաճառվի
                $updated = Product::whereKey($product->id)->where('stock', '>=', $item['qty'])->decrement('stock', $item['qty']);

                if ($updated === 0) {
                    // Բացառությունը → rollBack, իսկ ValidationException-ը → վերադարձ ֆորմա՝ սխալով
                    throw ValidationException::withMessages(['cart' => 'Պահեստում բավարար չէ՝ ' . $product->name]);
                }
            }

            return $order;
        });

        $this->cart->clear();
        session(['last_order' => $order->id]);

        return redirect()->route('checkout.thanks', $order);
    }

    public function thanks(Request $request, Order $order)
    {
        // ⭐ Միայն այս սեսիայի վերջին պատվերը կամ մուտք գործածի սեփականը
        $mine = session('last_order') === $order->id
            || ($request->user() !== null && $order->user_id === $request->user()->id);

        abort_unless($mine, 404);

        $order->load('items.product');

        return view('checkout.thanks', compact('order'));
    }
}
