<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Ընթերցանության օր — Դաս 09-ի reading_sessions-ը */
class ReadingSession extends Model
{
    protected $fillable = ['pages', 'read_on', 'note'];

    protected function casts(): array
    {
        return ['read_on' => 'date', 'pages' => 'integer'];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
