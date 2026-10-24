<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\ReadingList;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Կարդալու ցուցակ — PHP Դաս 19–21 */
class ListController extends Controller
{
    public function __construct(private readonly ReadingList $list) {}

    public function index()
    {
        $books = Book::active()->whereKey($this->list->ids())->get();      // WHERE id IN (...)՝ մեկ հարցումով

        return view('list.index', [
            'books'     => $books,
            'pagesLeft' => $books->sum(fn (Book $b) => $b->pagesLeft()),
            'perDay'    => fn (int $id) => $this->list->perDayOf($id),
        ]);
    }

    public function store(Request $request)
    {
        // Դաս 19-ի abort_if(..., 422)-ի փոխարեն՝ validate() (տնային 6)։ fetch-ին՝ JSON {message, errors}
        $request->validate(
            ['id' => ['required', 'integer', Rule::exists('books', 'id')->where('is_active', true)->whereNull('deleted_at')]],
            ['id.exists' => 'Այդպիսի գիրք դարակում չկա']
        );

        $book = Book::findOrFail($request->integer('id'));

        $added = $this->list->add($book->id);
        $message = $added ? "«{$book->title}»-ը ավելացվեց կարդալու ցուցակում" : 'Գիրքը արդեն ցուցակում է, կամ ցուցակը լի է';

        if ($request->expectsJson()) {
            return response()->json(['data' => ['count' => $this->list->count(), 'message' => $message]], $added ? 201 : 422);
        }

        return back()->with($added ? 'success' : 'error', $message);
    }

    public function destroy(int $id)
    {
        $this->list->remove($id);

        return back()->with('success', 'Հանվեց ցուցակից');
    }
}
