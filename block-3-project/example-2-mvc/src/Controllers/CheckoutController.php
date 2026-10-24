<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\Auth;
use App\Services\Cart;
use App\Services\CartSummary;
use App\Services\Validator;
use RuntimeException;

class CheckoutController
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly OrderRepository $orders,
        private readonly Cart $cart,
        private readonly Auth $auth,
    ) {}

    public function form(Request $request): Response
    {
        $items = $this->cart->items($this->products);

        if ($items === []) {
            flash('error', 'Զամբյուղը դատարկ է');
            return Response::redirect('/cart');
        }

        // Մուտք գործածին՝ լրացված անուն և հասցե
        $user = $this->auth->user();
        $old = ['delivery' => 'courier', 'payment' => 'cash', 'region' => 'Երևան',
                'name' => $user?->name ?? '', 'email' => $user?->email ?? ''];

        return $this->render($items, [], $old);
    }

    public function store(Request $request): Response
    {
        $items = $this->cart->items($this->products);

        if ($items === []) {
            return Response::redirect('/cart');
        }

        $data = $request->all();
        $errors = Validator::checkout($data);
        $summary = CartSummary::from($items, $request->input('delivery', 'courier'));

        if ($errors === []) {
            try {
                $id = $this->orders->create($data, $items, $summary, $this->auth->user()?->id);

                $this->cart->clear();
                $_SESSION['last_order'] = $id;

                return Response::redirect('/thanks/' . $id);
            } catch (RuntimeException $e) {
                $errors['_form'] = $e->getMessage();
            }
        }

        return $this->render($items, $errors, $data);
    }

    public function thanks(Request $request, string $id): Response
    {
        $order = ctype_digit($id) ? $this->orders->find((int) $id) : null;
        $user = $this->auth->user();

        // ⭐ Միայն այս սեսիայի վերջին պատվերը կամ մուտք գործածի սեփականը
        $mine = $order !== null && (
            ($_SESSION['last_order'] ?? null) === (int) $id
            || ($user !== null && (int) $order['user_id'] === $user->id)
        );

        if (!$mine) {
            throw new NotFoundException('Պատվերը չի գտնվել');
        }

        return Response::html(page('pages/thanks', ['order' => $order], 'Շնորհակալություն — ' . SITE_NAME));
    }

    private function render(array $items, array $errors, array $old): Response
    {
        return Response::html(page('pages/checkout', [
            'items'   => $items,
            'summary' => CartSummary::from($items, (string) ($old['delivery'] ?? 'courier')),
            'errors'  => $errors,
            'old'     => $old,
        ], 'Պատվերի ձևակերպում — ' . SITE_NAME));
    }
}
