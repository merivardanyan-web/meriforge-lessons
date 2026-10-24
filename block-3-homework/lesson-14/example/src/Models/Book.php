<?php
declare(strict_types=1);

namespace App\Models;

use App\Services\DurationFormatter;

// Գիրքը՝ abstract — PHP Դաս 13
// Ընդհանուրը՝ այստեղ։ Ինչը տարբեր է (տեսակ, ընթերցանության ժամանակ)՝ ժառանգներում

abstract class Book
{
    public const STATUS_LABELS = [
        'planned' => 'Կարդալու եմ',
        'reading' => 'Կարդում եմ',
        'done'    => 'Կարդացել եմ',
    ];

    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $author,
        public readonly ?int $year,
        public readonly int $pages,
        public readonly int $read,
        public readonly string $genre,
        public readonly int $rating,
        public readonly string $color,
        public readonly ?string $link,
        public readonly ?string $cover,
        public readonly string $createdAt,
    ) {}

    // ---------- Ֆաբրիկա՝ ընտրում է դասը ըստ format սյունակի ----------

    public static function fromRow(array $row): static
    {
        return match ($row['format'] ?? 'paper') {
            'audio' => AudioBook::fromRow($row),
            'ebook' => EBook::fromRow($row),
            default => PaperBook::fromRow($row),
        };
    }

    // ---------- Պարտադիր ամեն ժառանգում ----------

    abstract public function formatLabel(): string;

    /** Որքան ժամանակ է մնացել (րոպե)․ թղթայինը՝ էջերով, աուդիոն՝ տևողությունից */
    abstract public function minutesLeft(): int;

    // ---------- Ընդհանուր ----------

    public function status(): string
    {
        if ($this->read <= 0) {
            return 'planned';
        }

        return $this->read >= $this->pages ? 'done' : 'reading';
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status()];
    }

    public function progress(): int
    {
        if ($this->pages <= 0) {
            return 0;
        }

        return max(0, min(100, (int) ($this->read * 100 / $this->pages)));
    }

    public function pagesLeft(): int
    {
        return max(0, $this->pages - $this->read);
    }

    public function yearLabel(): string
    {
        return $this->year === null ? 'տարին անհայտ' : (string) $this->year;
    }

    public function isNew(): bool
    {
        // strtotime()՝ «2026-10-14 10:00:00» → վայրկյաններ 1970-ից, ինչպես time()-ը
        return strtotime($this->createdAt) > time() - 30 * 24 * 60 * 60;
    }

    public function shortTitle(int $max = 40): string
    {
        // mb_substr()՝ substr()-ի հայերեն-անվտանգ տարբերակը, ինչպես mb_strlen()-ը (Դաս 02)
        return mb_strlen($this->title) > $max ? mb_substr($this->title, 0, $max - 1) . '…' : $this->title;
    }

    public function stars(): string
    {
        if ($this->rating === 0) {
            return '';
        }

        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }

        return $stars;
    }

    /** «Մնաց՝ 3 ժ 20 ր»․ ձևաչափը՝ առանձին դասում (կոմպոզիցիա, տնային 9) */
    public function timeLeftLabel(): string
    {
        return (new DurationFormatter())->format($this->minutesLeft());
    }
}
