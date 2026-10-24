<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

/** Ադմին՝ գրքեր — PHP Դաս 19–21 */
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
        return view('admin.books.form', ['book' => new Book(['format' => 'paper', 'color' => '#003459', 'read_pages' => 0, 'rating' => 0]), 'genres' => Genre::orderBy('name')->get()]);
    }

    /** BookRequest-ը ստուգեց ԱՌԱՋ, քան մտանք այստեղ․ սխալի դեպքում՝ back()->withErrors()->withInput() */
    public function store(BookRequest $request)
    {
        $book = new Book($this->data($request));
        $book->slug = Book::uniqueSlugFor($book->title);
        $book->is_active = $request->boolean('is_active');          // fillable չէ → ուղիղ վերագրում
        $book->save();

        return redirect()->route('admin.books.index')->with('success', "Ավելացվեց՝ {$book->title}");
    }

    public function edit(Book $book)
    {
        return view('admin.books.form', ['book' => $book, 'genres' => Genre::orderBy('name')->get()]);
    }

    public function update(BookRequest $request, Book $book)
    {
        $book->fill($this->data($request, $book));
        $book->slug = Book::uniqueSlugFor($book->title, $book->id);
        $book->is_active = $request->boolean('is_active');

        // isDirty()՝ Դաս 11-ի rowCount()-ի թակարդի պատասխանը
        $changed = $book->isDirty();
        $book->save();

        return redirect()->route('admin.books.index')->with('success', $changed ? "Պահպանվեց՝ {$book->title}" : 'Ոչինչ չփոխվեց');
    }

    public function destroy(Book $book)
    {
        // ⭐ Երկրորդ ստուգում․ ուղին բաց է editor-ին (can:manage-books), իսկ ջնջելը՝ միայն admin-ին
        Gate::authorize('delete-books');

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', "«{$book->title}»-ը ջնջվեց");
    }

    public function trashed()
    {
        return view('admin.books.trashed', ['books' => Book::onlyTrashed()->latest('deleted_at')->get()]);
    }

    public function restore(Book $book)
    {
        Gate::authorize('delete-books');

        $book->restore();

        return redirect()->route('admin.books.index')->with('success', "«{$book->title}»-ը վերականգնվեց");
    }

    /**
     * validated()՝ միայն ստուգված դաշտերը, ոչ $request->all() ⭐
     * Շապիկը՝ store()-ով (տնային 5)․ Դաս 07-ի վեց քայլը (finfo, random անուն, move_uploaded_file)՝ մեկ տողում
     */
    private function data(BookRequest $request, ?Book $book = null): array
    {
        $data = $request->safe()->except(['cover', 'is_active']);

        if ($data['format'] !== 'audio') {
            $data['minutes'] = null;
            $data['narrator'] = null;
        }
        if ($data['format'] !== 'paper') {
            $data['lent_to'] = null;
        }

        if ($request->hasFile('cover')) {
            if ($book?->cover) {
                Storage::disk('public')->delete($book->cover);       // հինը՝ ջնջում ենք
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');   // covers/aB3x...jpg
        }

        return $data;
    }
}
