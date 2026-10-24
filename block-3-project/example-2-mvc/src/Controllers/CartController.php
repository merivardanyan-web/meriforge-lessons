<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Repositories\ProductRepository;
use App\Services\Cart;
use App\Services\CartSummary;

/** Զամբյուղ՝ ֆորմաներով։ Աշխատում է առանց JS-ի — PHP Դաս 17-ի progressive enhancement */
class CartController
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly Cart $cart,
    ) {}

    public function index(Request $request): Response
    {
        $items = $this->cart->items($this->products);

        return Response::html(page('pages/cart', [
            'items'   => $items,
            'summary' => CartSummary::from($items),
        ], 'Զամբյուղ — ' . SITE_NAME));
    }

    public function add(Request $request): Response
    {
        $product = $this->products->find($request->int('id'));
        $qty = $request->int('qty', 1);

        if ($product === null) {
            flash('error', 'Ապրանքը չի գտնվել');
        } elseif ($qty < 1 || $qty > 10) {
            flash('error', 'Քանակը՝ 1-ից 10');
        } elseif (!$product->inStock()) {
            flash('error', 'Ապրանքը սպառված է');
        } else {
            $this->cart->add($product->id, $qty, $product->maxQty());
            flash('success', 'Ավելացվեց զամբյուղ՝ ' . $product->name);
        }

        return Response::redirect(safeBack($request->input('back'), '/'));
    }

    public function update(Request $request): Response
    {
        $id = $request->int('id');
        $current = $this->cart->qtyOf($id);

        $qty = match ($request->input('action')) {
            'inc'   => $current + 1,
            'dec'   => $current - 1,
            default => $request->int('qty'),
        };

        $product = $this->products->find($id);
        $this->cart->setQty($id, $qty, $product?->maxQty() ?? 0);

        return Response::redirect('/cart');
    }

    public function remove(Request $request): Response
    {
        if ($request->input('all') === '1') {
            $this->cart->clear();
            flash('success', 'Զամբյուղը դատարկվեց');
        } else {
            $this->cart->remove($request->int('id'));
            flash('success', 'Ապրանքը հեռացվեց');
        }

        return Response::redirect('/cart');
    }
}
