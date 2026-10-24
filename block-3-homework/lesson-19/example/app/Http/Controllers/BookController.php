<?php

namespace App\Http\Controllers;

use App\Services\ReadingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Դարակ և գրքի էջ — PHP Դաս 18, 19 (Eloquent-ը՝ Դաս 20) */
class BookController extends Controller
{
    public const SORTS = [
        'progress' => ['Առաջընթաց',    'progress', 'desc'],
        'title'    => ['Այբբենական',   'title', 'asc'],
        'newest'   => ['Նորերը',       'created_at', 'desc'],
        'pages'    => ['Հաստից բարակ', 'pages', 'desc'],
    ];

    // ⭐ Autowiring․ Laravel-ը ինքն է ստեղծում ReadingList-ը և փոխանցում այստեղ (Դաս 15-ի bootstrap.php-ի աշխատանքը)
    public function __construct(private readonly ReadingList $list) {}

    public function index(Request $request)
    {
        return $this->shelf($request, null);
    }

    public function genre(Request $request, string $slug)
    {
        $genre = DB::table('genres')->where('slug', $slug)->first();

        abort_if($genre === null, 404);

        return $this->shelf($request, $genre);
    }

    public function show(string $slug)
    {
        $book = self::query()->where('books.slug', $slug)->first();

        abort_if($book === null, 404);

        return view('books.show', [
            'book'   => $book,
            'inList' => $this->list->has($book->id),
        ]);
    }

    /** Ընդհանուր հարցումը․ ListController-ը նույնպես օգտագործում է */
    public static function query()
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

        return view('books.index', [
            'books'  => self::query()
                ->when($genre, fn ($q) => $q->where('books.genre_id', $genre->id))
                ->orderBy($column, $direction)
                ->orderBy('books.id')
                ->paginate(6)
                ->withQueryString(),
            'genre'  => $genre,
            'genres' => DB::table('genres')->orderBy('name')->get(),
            'sort'   => $sort,
            'sorts'  => self::SORTS,
            'listIds' => $this->list->ids(),
        ]);
    }
}
