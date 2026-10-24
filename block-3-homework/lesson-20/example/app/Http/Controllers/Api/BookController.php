<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

/** GET /api/books, /api/books/{slug} — Դաս 17-ի BookApiController-ը, PHP Դաս 20 */
class BookController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min(50, max(1, (int) $request->query('per_page', 6)));     // ?per_page=-5 → 1

        $books = Book::active()
            ->with('genre')
            ->search($request->string('q')->toString())
            ->orderBy('title')
            ->paginate($perPage)
            ->withQueryString();

        // data + links + meta (current_page, last_page, total)՝ ինքնաբերաբար
        return BookResource::collection($books);
    }

    public function show(Book $book)
    {
        abort_unless($book->is_active, 404);

        return new BookResource($book->load('genre'));
    }
}
