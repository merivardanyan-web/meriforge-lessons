<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Անդամի գնահատականը — PHP Դաս 22 */
class Rating extends Model
{
    protected $fillable = ['stars'];            // user_id և book_id՝ միայն կոդից, ոչ ֆորմայից

    protected function casts(): array
    {
        return ['stars' => 'integer'];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
