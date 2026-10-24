<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Repositories\OrderRepository;
use App\Services\Auth;

/** Գնորդի էջ՝ իր պատվերները։ Ուրիշինը՝ երբեք (WHERE user_id = ...) */
class AccountController
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly Auth $auth,
    ) {}

    public function index(Request $request): Response
    {
        $user = $this->auth->user();

        if ($user === null) {
            $_SESSION['intended'] = '/account';
            return Response::redirect('/login');
        }

        return Response::html(page('pages/account', [
            'user'   => $user,
            'orders' => $this->orders->forUser($user->id),
        ], 'Իմ հաշիվը — ' . SITE_NAME));
    }
}
