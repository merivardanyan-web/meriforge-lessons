<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;

/** Ադմին՝ գրքեր — PHP Դաս 19, 20 (store և update՝ Դաս 21) */
class BookController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        return view('admin.books.index', [
            'books' => Book::with('genre')->search($q)->orderByDesc('is_active')->orderBy('title')->get(),
            'q'     => $q,
        ]);
    }

    public function create()
    {
        return view('admin.books.form', ['book' => new Book(['format' => 'paper', 'color' => '#003459']), 'genres' => Genre::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        dd($request->method(), $request->except('_token'));       // Դաս 21-ում՝ BookRequest
    }

    /** Book $book՝ id-ով (ադմինում slug-ը կարող է փոխվել) */
    public function edit(Book $book)
    {
        return view('admin.books.form', ['book' => $book, 'genres' => Genre::orderBy('name')->get()]);
    }

    public function update(Request $request, Book $book)
    {
        dd($request->method(), $request->except('_token'));
    }

    /** SoftDeletes․ DELETE-ի փոխարեն UPDATE deleted_at = NOW() — Դաս 11-ի is_active = 0-ի նման */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', "«{$book->title}»-ը ջնջվեց։ Վերականգնել՝ «Ջնջվածներ» էջից");
    }

    /** Ջնջվածներ — տնային 6 */
    public function trashed()
    {
        return view('admin.books.trashed', ['books' => Book::onlyTrashed()->latest('deleted_at')->get()]);
    }

    public function restore(Book $book)
    {
        $book->restore();

        return redirect()->route('admin.books.index')->with('success', "«{$book->title}»-ը վերականգնվեց");
    }
}
