<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\Validator;

class ContactController
{
    public function form(Request $request): Response
    {
        return $this->render([], []);
    }

    public function submit(Request $request): Response
    {
        $errors = Validator::contact($request->all());

        if ($errors === []) {
            flash('success', 'Շնորհակալություն։ Կպատասխանենք մեկ աշխատանքային օրվա ընթացքում։');
            return Response::redirect('/contact');
        }

        return $this->render($errors, $request->all());
    }

    /** Թեմա cookie-ով — PHP Դաս 7 */
    public function theme(Request $request): Response
    {
        $theme = $request->input('theme') === 'dark' ? 'dark' : 'light';

        setcookie('theme', $theme, [
            'expires'  => time() + 60 * 60 * 24 * 365,
            'path'     => '/',
            'httponly' => false,
            'samesite' => 'Lax',
        ]);

        return Response::redirect(safeBack($request->input('back'), '/'));
    }

    private function render(array $errors, array $old): Response
    {
        return Response::html(page('pages/contact', [
            'errors' => $errors,
            'old'    => $old,
            'topics' => Validator::CONTACT_TOPICS,
        ], 'Կապ — ' . SITE_NAME));
    }
}
