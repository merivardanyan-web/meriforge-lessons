<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Դաս 12–13-ի Book, PaperBook, AudioBook և Դաս 14-ի BookRepository-ն՝ մեկ մոդելում — PHP Դաս 20
 * Տեսակները՝ format սյունակով։ Ժառանգումը Eloquent-ում հնարավոր է, բայց այստեղ չափազանց կլիներ
 */
class Book extends Model
{
    use HasFactory, SoftDeletes;

    public const FORMATS = ['paper' => '📖 Թղթային', 'audio' => '🎧 Աուդիո', 'ebook' => '💻 Էլեկտրոնային'];

    public const STATUS_LABELS = ['planned' => 'Կարդալու եմ', 'reading' => 'Կարդում եմ', 'done' => 'Կարդացել եմ'];

    /** Mass assignment-ի սպիտակ ցուցակ․ slug-ը և is_active-ը՝ ոչ (Դաս 11-ի bookData()-ի պես) */
    protected $fillable = [
        'genre_id', 'title', 'author', 'year', 'pages', 'read_pages', 'rating',
        'color', 'link', 'format', 'minutes', 'narrator', 'lent_to', 'cover',
    ];

    protected function casts(): array
    {
        return [
            'year'       => 'integer',
            'pages'      => 'integer',
            'read_pages' => 'integer',
            'rating'     => 'integer',
            'minutes'    => 'integer',
            'is_active'  => 'boolean',
        ];
    }

    // ---------- Route model binding՝ slug-ով, ոչ id-ով ----------
    // /book/{book:slug}-ը նույնն է անում ուղու մակարդակում։ Ադմինը մնում է id-ով

    // ---------- Կապեր ----------

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ReadingSession::class)->latest('read_on');
    }

    // ---------- Scope-ներ ----------

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** Դաս 10-ի STATUS_SQL-ը */
    public function scopeStatus(Builder $query, ?string $status): void
    {
        match ($status) {
            'planned' => $query->where('read_pages', 0),
            'reading' => $query->where('read_pages', '>', 0)->whereColumn('read_pages', '<', 'pages'),
            'done'    => $query->whereColumn('read_pages', '>=', 'pages'),
            default   => null,
        };
    }

    /** Դաս 10-ի որոնումը․ %-ը և _-ը տեքստում՝ բառացի */
    public function scopeSearch(Builder $query, ?string $text): void
    {
        $text = trim((string) $text);

        if ($text === '') {
            return;
        }

        $like = '%' . addcslashes($text, '%_\\') . '%';
        $query->where(fn (Builder $w) => $w->where('title', 'like', $like)->orWhere('author', 'like', $like));
    }

    // ---------- Դաս 12–13-ի մեթոդները ----------

    public function status(): string
    {
        if ($this->read_pages <= 0) {
            return 'planned';
        }

        return $this->read_pages >= $this->pages ? 'done' : 'reading';
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status()];
    }

    public function progress(): int
    {
        return $this->pages > 0 ? max(0, min(100, intdiv($this->read_pages * 100, $this->pages))) : 0;
    }

    public function pagesLeft(): int
    {
        return max(0, $this->pages - $this->read_pages);
    }

    public function formatLabel(): string
    {
        return self::FORMATS[$this->format] ?? self::FORMATS['paper'];
    }

    public function isAudio(): bool
    {
        return $this->format === 'audio';
    }

    public function isLent(): bool
    {
        return $this->format === 'paper' && $this->lent_to !== null;
    }

    /** Դաս 13-ի minutesLeft()-ը․ թղթայինը՝ 2 րոպե էջին, աուդիոն՝ տևողության մնացածը */
    public function minutesLeft(): int
    {
        return $this->isAudio()
            ? intdiv((int) $this->minutes * (100 - $this->progress()), 100)
            : $this->pagesLeft() * 2;
    }

    public function yearLabel(): string
    {
        return $this->year === null ? 'տարին անհայտ' : (string) $this->year;
    }

    public function isNew(): bool
    {
        return $this->created_at?->gt(now()->subDays(30)) ?? false;      // Carbon, ոչ strtotime
    }

    public function coverUrl(): ?string
    {
        return $this->cover ? asset('storage/' . $this->cover) : null;     // Դաս 21․ storage:link
    }

    /** Դաս 11-ի uniqueSlug()-ը․ ջնջվածները նույնպես հաշվում ենք (withTrashed), որ UNIQUE-ը չխախտվի */
    public static function uniqueSlugFor(string $title, ?int $exceptId = null): string
    {
        $base = slugify($title);                 // app/helpers.php․ Str::slug-ը «ու»-ն չգիտի
        $slug = $base;
        $i = 1;

        while (static::withTrashed()->where('slug', $slug)->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
