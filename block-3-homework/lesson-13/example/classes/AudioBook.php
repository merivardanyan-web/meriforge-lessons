<?php
declare(strict_types=1);

// Աուդիոգիրք — PHP Դաս 13․ pages-ը՝ տպագիր տարբերակի էջերը, իսկ ժամանակը՝ րոպեներով
class AudioBook extends Book implements Listenable
{
    public function __construct(
        int $id,
        string $title,
        string $slug,
        string $author,
        ?int $year,
        int $pages,
        int $read,
        string $genre,
        int $rating,
        string $color,
        ?string $link,
        ?string $cover,
        string $createdAt,
        public readonly int $minutes = 0,
        public readonly ?string $narratorName = null,
    ) {
        parent::__construct($id, $title, $slug, $author, $year, $pages, $read, $genre, $rating, $color, $link, $cover, $createdAt);
    }

    public static function fromRow(array $row): static
    {
        return new static(
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
            minutes: (int) ($row['minutes'] ?? 0),
            narratorName: $row['narrator'] ?? null,
        );
    }

    public function formatLabel(): string
    {
        return '🎧 Աուդիո';
    }

    /** Լսածը՝ նույն համամասնությամբ, ինչ կարդացած էջերը */
    public function minutesLeft(): int
    {
        return (int) ($this->minutes * (100 - $this->progress()) / 100);
    }

    public function durationMinutes(): int
    {
        return $this->minutes;
    }

    public function narrator(): ?string
    {
        return $this->narratorName;
    }
}
