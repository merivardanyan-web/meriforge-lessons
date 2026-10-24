<?php
declare(strict_types=1);

// Թղթային գիրք — PHP Դաս 13․ կարելի է տալ ընկերոջը
class PaperBook extends Book implements Lendable
{
    public const MINUTES_PER_PAGE = 2;

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
        public readonly ?string $lentToName = null,
    ) {
        // ⭐ Առանց սրա ծնողի readonly դաշտերը չեն լրացվի (տնային 3)
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
            lentToName: $row['lent_to'] ?? null,
        );
    }

    public function formatLabel(): string
    {
        return '📖 Թղթային';
    }

    public function minutesLeft(): int
    {
        return $this->pagesLeft() * self::MINUTES_PER_PAGE;
    }

    public function isLent(): bool
    {
        return $this->lentToName !== null;
    }

    public function lentTo(): ?string
    {
        return $this->lentToName;
    }
}
