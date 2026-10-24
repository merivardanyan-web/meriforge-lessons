<?php

namespace App\Http\Controllers;

use App\Services\ReadingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Կարդալու ցուցակ — PHP Դաս 19 (Դաս 15-ի ListController-ը) */
class ListController extends Controller
{
    public function __construct(private readonly ReadingList $list) {}

    public function index()
    {
        $books = BookController::query()->whereIn('books.id', $this->list->ids())->get();

        return view('list.index', [
            'books'     => $books,
            'pagesLeft' => $books->sum(fn ($b) => $b->pages - $b->read_pages),     // Collection, ոչ array_reduce
            'perDay'    => fn (int $id) => $this->list->perDayOf($id),
        ]);
    }

    public function store(Request $request)
    {
        $id = (int) $request->input('id');
        $book = BookController::query()->where('books.id', $id)->first();

        abort_if($book === null, 404);

        $added = $this->list->add($book->id);
        $message = $added ? "«{$book->title}»-ը ավելացվեց կարդալու ցուցակում" : 'Գիրքը արդեն ցուցակում է, կամ ցուցակը լի է';

        // Նույն ուղին՝ ֆորմայի և fetch-ի համար (Դաս 17-ի /list/add և /api/list/items-ը միասին)
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
