<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

/** «What I added»․ անդամը գնահատում է գիրքը — PHP Դաս 22 */
class RatingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        abort_unless($book->is_active, 404);

        $data = $request->validate(['stars' => ['required', 'integer', 'between:1,5']], [], ['stars' => 'գնահատականը']);

        // Կա՞ արդեն՝ թարմացնում ենք, չկա՝ ստեղծում։ UNIQUE(user_id, book_id)-ը երաշխավորում է նույնը բազայում
        // ⚠️ updateOrCreate(['user_id' => ...])-ը կփորձեր user_id-ն լցնել fill()-ով, իսկ այն fillable չէ (դիտավորյալ)․
        //    shouldBeStrict()-ով դա MassAssignmentException է։ Ուստի user_id-ն՝ ուղիղ վերագրմամբ, ինչպես role-ը (Դաս 20)
        $rating = $book->ratings()->where('user_id', $request->user()->id)->first() ?? $book->ratings()->make();
        $rating->user_id = $request->user()->id;
        $rating->stars = $data['stars'];
        $rating->save();

        return redirect()->route('book.show', $book)->with('success', 'Շնորհակալություն, գնահատականը պահպանվեց');
    }
}
