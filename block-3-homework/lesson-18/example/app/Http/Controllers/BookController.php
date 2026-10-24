<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Դաս 15-ի BookController-ը՝ Laravel-ում — PHP Դաս 18
 * Eloquent դեռ չկա (Դաս 20)․ աշխատում ենք Դաս 09-ի ani_books բազայի հետ DB::table-ով
 */
class BookController extends Controller
{
    /** Սպիտակ ցուցակ․ orderBy($request->query('sort'))-ը կտար ?sort=password (տնային 5) ⭐ */
    public const SORTS = [
        'progress' => ['Առաջընթաց',    'progress', 'desc'],
        'title'    => ['Այբբենական',   'title', 'asc'],
        'newest'   => ['Նորերը',       'created_at', 'desc'],
        'pages'    => ['Հաստից բարակ', 'pages', 'desc'],
    ];

    public function index(Request $request)
    {
        return $this->shelf($request, null);
    }

    /** /genre/patmavep — տնային 3 */
    public function genre(Request $request, string $slug)
    {
        $genre = DB::table('genres')->where('slug', $slug)->first();

        abort_if($genre === null, 404);                 // Դաս 15-ի NotFoundException-ի փոխարեն

        return $this->shelf($request, $genre);
    }

    public function show(string $slug)
    {
        $book = $this->query()->where('books.slug', $slug)->first();

        abort_if($book === null, 404);

        return view('books.show', ['book' => $book]);
    }

    // ---------- Ներքին ----------

    /** Դաս 14-ի SELECT_FIELDS-ը և FROM-ը՝ Query Builder-ով */
    private function query()
    {
        return DB::table('books')
            ->leftJoin('genres', 'genres.id', '=', 'books.genre_id')
            ->select('books.*', 'genres.name as genre', 'genres.slug as genre_slug')
            ->selectRaw('ROUND(books.read_pages * 100 / books.pages) AS progress')
            ->where('books.is_active', 1);
    }

    private function shelf(Request $request, ?object $genre)
    {
        $sort = $request->query('sort');
        $sort = is_string($sort) && isset(self::SORTS[$sort]) ? $sort : 'progress';
        [, $column, $direction] = self::SORTS[$sort];

        $books = $this->query()
            ->when($genre, fn ($q) => $q->where('books.genre_id', $genre->id))
            ->orderBy($column, $direction)
            ->orderBy('books.id')
            ->paginate(6)
            ->withQueryString();                         // ?sort=-ը մնում է երկրորդ էջում

        return view('books.index', [
            'books'  => $books,
            'genre'  => $genre,
            'genres' => DB::table('genres')->orderBy('name')->get(),
            'sort'   => $sort,
            'sorts'  => self::SORTS,
        ]);
    }
}
