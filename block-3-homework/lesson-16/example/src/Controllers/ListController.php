<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Models\Book;
use App\Models\ListItem;
use App\Repositories\BookRepository;
use App\Services\ReadingList;

/** Կարդալու ցուցակ — PHP Դաս 07, 12, 15 */
class ListController
{
    public function __construct(
        private readonly BookRepository $books,
        private readonly ReadingList $list,
    ) {}

    public function index(Request $request): Response
    {
        $items = $this->list->items($this->books);

        return Response::html(page('pages/list', [
            'items'     => $items,
            'pagesLeft' => array_reduce($items, fn(int $sum, ListItem $i): int => $sum + $i->book->pagesLeft(), 0),
            'listening' => totalListening(array_map(fn(ListItem $i): Book => $i->book, $items)),
        ], 'Կարդալու ցուցակ — ' . SITE_NAME, 'list'));
    }

    public function add(Request $request): Response
    {
        $book = $this->books->find($request->int('id'));

        if ($book === null) {
            flash('error', 'Գիրքը չի գտնվել');
        } elseif ($this->list->add($book->id)) {
            flash('success', '«' . $book->title . '»-ը ավելացվեց կարդալու ցուցակում');
        } else {
            flash('error', 'Գիրքը արդեն ցուցակում է, կամ ցուցակը լի է (' . ReadingList::MAX . ')');
        }

        return Response::redirect(safeBack($request->input('back')));
    }

    public function remove(Request $request): Response
    {
        $this->list->remove($request->int('id'));
        flash('success', 'Հանվեց ցուցակից');

        return Response::redirect(safeBack($request->input('back'), '/list'));
    }

    public function goal(Request $request): Response
    {
        $pages = $request->int('per_day');

        if (!$this->list->setPerDay($request->int('id'), $pages)) {
            flash('error', 'Օրական էջերը՝ 1-ից 200');
        } else {
            flash('success', 'Նպատակը պահպանվեց՝ օրական ' . $pages . ' էջ');
        }

        return Response::redirect('/list');
    }
}
