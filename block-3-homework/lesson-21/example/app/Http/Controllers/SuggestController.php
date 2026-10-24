<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuggestRequest;

/** «Առաջարկիր գիրք» — Դաս 06-ի suggest.php-ն, PHP Դաս 21, տնային 2 */
class SuggestController extends Controller
{
    public function create()
    {
        return view('suggest', ['genres' => SuggestRequest::GENRES]);
    }

    public function store(SuggestRequest $request)
    {
        // Այստեղ կուղարկեինք նամակ Անիին՝ Mail::to(...)->send(...)
        return redirect()->route('suggest')->with('success', 'Շնորհակալություն։ Անին կկարդա առաջարկդ այս շաբաթ։');
    }
}
