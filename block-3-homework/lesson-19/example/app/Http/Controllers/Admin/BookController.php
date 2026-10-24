<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * php artisan make:controller Admin/BookController --resource — PHP Դաս 19
 * store և update՝ Դաս 21-ում (Form Request), destroy՝ Դաս 20-ում (Eloquent, SoftDeletes)
 */
class BookController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');

        $books = DB::table('books')
            ->leftJoin('genres', 'genres.id', '=', 'books.genre_id')
            ->select('books.id', 'books.title', 'books.author', 'books.pages', 'books.read_pages', 'books.is_active', 'genres.name as genre')
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('title', 'like', "%{$q}%")->orWhere('author', 'like', "%{$q}%")))
            ->orderByDesc('is_active')
            ->orderBy('title')
            ->get();

        return view('admin.books.index', compact('books', 'q'));
    }

    public function create()
    {
        return view('admin.books.form', [
            'book'   => null,
            'genres' => DB::table('genres')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        dd($request->method(), $request->except('_token'));       // Դաս 21-ում՝ BookRequest
    }

    public function edit(string $id)
    {
        $book = DB::table('books')->find($id);

        abort_if($book === null, 404);

        return view('admin.books.form', [
            'book'   => $book,
            'genres' => DB::table('genres')->orderBy('name')->get(),
        ]);
    }

    /** Դասարանական, փորձ 3․ dd()-ն ցույց կտա "PUT", իսկ Network-ում՝ POST (@method('PUT')) */
    public function update(Request $request, string $id)
    {
        dd($request->method(), $request->except('_token'));
    }

    public function destroy(string $id)
    {
        return back()->with('error', 'Թաքցնելը՝ Դաս 20-ում (SoftDeletes)');
    }
}
