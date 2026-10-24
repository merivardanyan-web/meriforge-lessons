<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Exceptions\HttpException;
use App\Exceptions\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Models\ListItem;
use App\Repositories\BookRepository;
use App\Services\ReadingList;

/** Կարդալու ցուցակը JSON-ով — PHP Դաս 17 */
class ListApiController
{
    public function __construct(
        private readonly BookRepository $books,
        private readonly ReadingList $list,
    ) {}

    /** GET /api/list — տնային 2 */
    public function index(Request $request): Response
    {
        $items = $this->list->items($this->books);       // findMany()՝ մեկ հարցումով (Դաս 14)

        return Response::json([
            'data' => array_map(fn(ListItem $i): array => $i->book->toArray() + [
                'per_day'   => $i->perDay,
                'days_left' => $i->daysLeft(),
            ], $items),
            'meta' => [
                'count'      => count($items),
                'pages_left' => array_reduce($items, fn(int $sum, ListItem $i): int => $sum + $i->book->pagesLeft(), 0),
            ],
        ]);
    }

    /** POST /api/list/items  {"id": 2} */
    public function add(Request $request): Response
    {
        $book = $this->books->find($request->int('id')) ?? throw new NotFoundException('Գիրքը չի գտնվել');

        if (!$this->list->add($book->id)) {
            throw new HttpException('Գիրքը արդեն ցուցակում է, կամ ցուցակը լի է', 422);
        }

        return Response::json(['data' => [
            'count'   => $this->list->count(),
            'message' => '«' . $book->title . '»-ը ավելացվեց կարդալու ցուցակում',
        ]], 201);
    }

    /** DELETE /api/list/items/{id} — տնային 3 */
    public function remove(Request $request, string $id): Response
    {
        $this->list->remove((int) $id);

        return Response::json(['data' => ['count' => $this->list->count()]]);
    }
}
