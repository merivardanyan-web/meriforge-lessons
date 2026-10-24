<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Exceptions\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Models\Book;
use App\Repositories\BookRepository;

/** GET /api/books, /api/books/{slug} — PHP Դաս 17 */
class BookApiController
{
    private const MAX_PER_PAGE = 50;

    public function __construct(private readonly BookRepository $books) {}

    public function index(Request $request): Response
    {
        $filters = BookRepository::filtersFrom($request->allQuery());
        $sort    = $request->query('sort');
        $sort    = isset(BookRepository::SORTS[$sort]) ? $sort : 'progress';
        $page    = max(1, $request->int('page', 1));                                   // ?page=abc → 1 (տնային 9)
        $perPage = min(self::MAX_PER_PAGE, max(1, $request->int('per_page', 6)));      // ?per_page=-5 → 1, 1000 → 50

        $total = $this->books->count($filters);
        $found = $this->books->search($filters, $sort, $perPage, ($page - 1) * $perPage);

        return Response::json([
            // array_map-ը պահում է 0, 1, 2 բանալիները → JSON զանգված [...]։
            // array_filter-ից հետո բանալիները կարող են լինել 0, 2 → JSON օբյեկտ {"0":..,"2":..} (տնային 6)
            'data' => array_map(fn(Book $b): array => $b->toArray(), $found),
            'meta' => [
                'page'      => $page,
                'per_page'  => $perPage,
                'total'     => $total,
                'last_page' => max(1, (int) (($total + $perPage - 1) / $perPage)),
            ],
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $book = $this->books->findBySlug($slug) ?? throw new NotFoundException('Գիրքը չի գտնվել');

        return Response::json(['data' => $book->toArray()]);
    }
}
