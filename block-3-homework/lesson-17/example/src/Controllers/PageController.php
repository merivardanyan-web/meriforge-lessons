<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\Validator;

/** Պարզ էջեր, առաջարկի ֆորմա և թեմա — PHP Դաս 05–07, 15 */
class PageController
{
    public function about(Request $request): Response
    {
        return Response::html(page('pages/about', [], 'Ակումբի մասին — ' . SITE_NAME, 'about'));
    }

    public function suggestForm(Request $request): Response
    {
        return $this->suggestPage([], []);
    }

    public function suggest(Request $request): Response
    {
        $errors = Validator::suggestion($request->all());

        if ($errors === []) {
            flash('success', 'Շնորհակալություն։ Անին կկարդա առաջարկդ այս շաբաթ։');
            return Response::redirect('/suggest');
        }

        return $this->suggestPage($errors, $request->all(), 422);
    }

    public function theme(Request $request): Response
    {
        setcookie('theme', isDark() ? 'light' : 'dark', [
            'expires'  => time() + 60 * 60 * 24 * 365,
            'path'     => '/',
            'samesite' => 'Lax',
        ]);

        return Response::redirect(safeBack($request->input('back')));
    }

    private function suggestPage(array $errors, array $old, int $status = 200): Response
    {
        return Response::html(page('pages/suggest', [
            'errors' => $errors,
            'old'    => $old,
            'genres' => Validator::GENRES,
        ], 'Առաջարկիր գիրք — ' . SITE_NAME, 'suggest'), $status);
    }
}
