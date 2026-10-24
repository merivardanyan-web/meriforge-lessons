<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Request;
use App\Http\Response;
use App\Models\CartItem;
use App\Repositories\ProductRepository;
use App\Services\Cart;
use App\Services\CartSummary;

/** Զամբյուղ՝ JSON-ով — PHP Դաս 17։ Նույն կանոնները, ինչ CartController-ում */
class CartApiController
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly Cart $cart,
    ) {}

    /** GET /api/cart — տողերը և գումարը — Դաս 17-ի տնային */
    public function index(Request $request): Response
    {
        return Response::json(['data' => $this->payload()]);
    }

    public function add(Request $request): Response
    {
        $id  = $request->int('id');
        $qty = $request->int('qty', 1);

        $product = $this->products->find($id) ?? throw new NotFoundException('Ապրանքը չի գտնվել');

        $errors = [];

        if ($qty < 1 || $qty > 10) {
            $errors['qty'] = 'Քանակը՝ 1-ից 10';
        }

        if (!$product->inStock()) {
            $errors['id'] = 'Ապրանքը առկա չէ';
        }

        if ($errors !== []) {
            throw new ValidationException($errors);
        }

        $this->cart->add($id, $qty, $product->maxQty());

        return Response::json(['data' => $this->payload() + ['message' => 'Ավելացվեց՝ ' . $product->name]]);
    }

    /** DELETE /api/cart/items/{id} — Դաս 17-ի տնային */
    public function remove(Request $request, string $id): Response
    {
        $this->cart->remove((int) $id);

        return Response::json(['data' => $this->payload() + ['message' => 'Հեռացվեց']]);
    }

    private function payload(): array
    {
        $items = $this->cart->items($this->products);

        return [
            'count'   => $this->cart->count(),
            'items'   => array_map(fn(CartItem $item): array => [
                'product' => $item->product->toArray(),
                'qty'     => $item->qty,
                'sum'     => $item->sum(),
            ], $items),
            'summary' => CartSummary::from($items)->toArray(),
        ];
    }
}
