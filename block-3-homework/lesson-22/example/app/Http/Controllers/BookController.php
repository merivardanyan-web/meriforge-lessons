<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Services\ReadingList;
use Illuminate\Http\Request;

/** Դարակ և գրքի էջ — PHP Դաս 18–22։ DB::table-ի փոխարեն՝ Eloquent */
class BookController extends Controller
{
    public const SORTS = [
        'progress' => ['Առաջընթաց',    'ROUND(read_pages * 100 / pages) DESC'],
        'title'    => ['Այբբենական',   'title ASC'],
        'newest'   => ['Նորերը',       'created_at DESC'],
        'pages'    => ['Հաստից բարակ', 'pages DESC'],
    ];

    public function __construct(private readonly ReadingList $list) {}

    public function index(Request $request)
    {
        return $this->shelf($request, null);
    }

    /** Genre $genre՝ route model binding-ով․ չգտնվեց → 404 ինքնաբերաբար */
    public function genre(Request $request, Genre $genre)
    {
        return $this->shelf($request, $genre);
    }

    public function show(Book $book)
    {
        abort_unless($book->is_active, 404);          // ⭐ թաքցվածը՝ 404 (դասարանական, փորձ 4)

        $book->load(['genre', 'sessions'])
            ->loadAvg('ratings', 'stars')          // ratings_avg_stars՝ մեկ AVG() հարցումով
            ->loadCount('ratings');                // ratings_count

        return view('books.show', [
            'book'     => $book,
            'inList'   => $this->list->has($book->id),
            'myRating' => auth()->check() ? $book->ratings()->where('user_id', auth()->id())->value('stars') : null,
        ]);
    }

    private function shelf(Request $request, ?Genre $genre)
    {
        $sort = $request->query('sort');
        $sort = is_string($sort) && isset(self::SORTS[$sort]) ? $sort : 'progress';

        // $genre->books()-ը արդեն WHERE genre_id = ? է (տնային 4)
        $query = $genre ? $genre->books() : Book::query();

        $books = $query
            ->active()
            ->with('genre')                          // ⭐ առանց սրա՝ N+1 (դասարանական, փորձ 1)
            ->search($request->string('q')->toString())
            ->status($request->string('status')->toString())
            ->orderByRaw(self::SORTS[$sort][1])      // մեր տեքստը, ոչ օգտագործողի
            ->orderBy('id')
            ->paginate(6)
            ->withQueryString();

        return view('books.index', [
            'books'   => $books,
            'genre'   => $genre,
            'genres'  => Genre::orderBy('name')->get(),
            'sort'    => $sort,
            'sorts'   => self::SORTS,
            'listIds' => $this->list->ids(),
        ]);
    }
}
