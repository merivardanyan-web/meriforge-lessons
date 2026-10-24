<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Http\Response;
use App\Repositories\OrderRepository;

class OrderController
{
    public function __construct(
        private readonly OrderRepository $orders,
    ) {}

    public function index(Request $request): Response
    {
        return Response::html(page('admin/orders', [
            'orders' => $this->orders->all(),
            'stats'  => $this->orders->stats(),
        ], 'Պատվերներ', 'layout-admin'));
    }

    public function status(Request $request, string $id): Response
    {
        $ok = ctype_digit($id) && $this->orders->updateStatus((int) $id, $request->input('status'));

        flash($ok ? 'success' : 'error', $ok ? 'Կարգավիճակը թարմացվեց' : 'Կարգավիճակը չփոխվեց');

        return Response::redirect('/admin/orders');
    }
}
