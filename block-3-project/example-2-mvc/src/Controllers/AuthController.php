<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\Auth;
use App\Services\LoginThrottle;

/** Մուտք և ելք — PHP Դաս 16 */
class AuthController
{
    public function __construct(
        private readonly Auth $auth,
        private readonly LoginThrottle $throttle,
    ) {}

    public function form(Request $request): Response
    {
        if ($this->auth->check()) {
            return Response::redirect($this->auth->isStaff() ? '/admin' : '/');
        }

        return Response::html(page('pages/login', [
            'error' => flash('auth_error'),
            'email' => flash('auth_email') ?? '',
        ], 'Մուտք — ' . SITE_NAME));
    }

    public function login(Request $request): Response
    {
        if ($this->throttle->tooManyAttempts()) {
            flash('auth_error', 'Չափազանց շատ փորձեր։ Փորձեք 15 րոպեից։');
            return Response::redirect('/login');
        }

        $email = $request->input('email');

        if (!$this->auth->attempt($email, $request->input('password'))) {
            $this->throttle->hit();

            // ⭐ Ընդհանուր հաղորդագրություն․ չենք ասում՝ հասցե՞ն է սխալ, թե՞ գաղտնաբառը
            flash('auth_error', 'Սխալ էլ. հասցե կամ գաղտնաբառ');
            flash('auth_email', $email);

            return Response::redirect('/login');
        }

        $this->throttle->clear();

        $to = $_SESSION['intended'] ?? ($this->auth->isStaff() ? '/admin' : '/');
        unset($_SESSION['intended']);

        flash('success', 'Բարի գալուստ, ' . $this->auth->user()->name);

        return Response::redirect($to);
    }

    public function logout(Request $request): Response
    {
        $this->auth->logout();

        return Response::redirect('/');
    }
}
