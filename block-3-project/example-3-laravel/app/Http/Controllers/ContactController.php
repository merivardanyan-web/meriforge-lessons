<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function form()
    {
        return view('contact', ['topics' => ContactRequest::TOPICS]);
    }

    /** Դաս 6-ի 70 տողը՝ մեկ ContactRequest-ով — PHP Դաս 21-ի տնային */
    public function submit(ContactRequest $request)
    {
        // Այստեղ ենք հասնում ՄԻԱՅՆ եթե ամեն ինչ վավեր է
        // Mail::to(...)->send(...) — լոկալ՝ MAIL_MAILER=log (Դաս 22-ի տնային)

        return redirect()->route('contact')->with('success', 'Շնորհակալություն։ Կպատասխանենք մեկ աշխատանքային օրվա ընթացքում։');
    }

    /** Թեմա cookie-ով — Դաս 7 */
    public function theme(Request $request)
    {
        $theme = $request->input('theme') === 'dark' ? 'dark' : 'light';

        // Laravel-ը cookie-ները գաղտնագրում է․ թեման ամեն էջում կարդում ենք՝ request()->cookie('theme')
        return back()->withCookie(cookie('theme', $theme, 60 * 24 * 365));
    }
}
