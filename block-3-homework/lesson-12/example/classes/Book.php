<?php
declare(strict_types=1);

// Գիրքը՝ օբյեկտ — PHP Դաս 12
// Զանգվածում ամեն ինչ կարելի էր գրել ($book['prise'] = ...), իսկ այստեղ դաշտերը հայտնի են և readonly

class Book
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

    /** Բազայի տողից։ Ամբողջ «(int)»-ների աշխատանքը՝ մեկ տեղում (Դաս 10-ի normalizeBook-ի փոխարեն) */
    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            title: $row['title'],
            slug: $row['slug'],
            author: $row['author'],
            year: $row['year'] === null ? null : (int) $row['year'],
            pages: (int) $row['pages'],
            read: (int) $row['read_pages'],
            genre: $row['genre'] ?? 'առանց ժանրի',
            rating: (int) $row['rating'],
            color: $row['color'],
            link: $row['link'],
            cover: $row['cover'],
            createdAt: $row['created_at'],
        );
    }

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

    /** 0–100, Դաս 04-ի ուղղումներով */
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

    // ---------- Տնային 2 ----------

    /** Ավելացվել է վերջին 30 օրում */
    public function isNew(): bool
    {
        // strtotime()՝ «2026-10-14 10:00:00» → վայրկյաններ 1970-ից, ինչպես time()-ը
        return strtotime($this->createdAt) > time() - 30 * 24 * 60 * 60;
    }

    /** Երկար վերնագիրը՝ 40 նիշից կտրած, քարտի համար */
    public function shortTitle(int $max = 40): string
    {
        // mb_substr()՝ substr()-ի հայերեն-անվտանգ տարբերակը, ինչպես mb_strlen()-ը (Դաս 02)
        return mb_strlen($this->title) > $max ? mb_substr($this->title, 0, $max - 1) . '…' : $this->title;
    }

    /** ★★★★☆ կամ դատարկ, եթե դեռ չեմ գնահատել */
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
}
