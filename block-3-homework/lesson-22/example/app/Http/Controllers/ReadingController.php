<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReadingRequest;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

/** «Կարդացի այսօր» — Դաս 11-ի logReading()-ը, PHP Դաս 21 */
class ReadingController extends Controller
{
    public function store(ReadingRequest $request, Book $book)
    {
        // DB::transaction՝ beginTransaction/commit/rollBack-ը մեկ տողում․ բացառության դեպքում՝ rollBack ինքնաբերաբար
        DB::transaction(function () use ($request, $book) {
            $book->sessions()->create($request->validated());

            $updated = Book::whereKey($book->id)
                ->whereRaw('read_pages + ? <= pages', [$request->integer('pages')])
                ->increment('read_pages', $request->integer('pages'));

            abort_if($updated === 0, 422, 'Էջերը շատ են');
        });

        return redirect()->route('book.show', $book)->with('success', '+' . $request->integer('pages') . " էջ «{$book->title}»-ից");
    }
}
