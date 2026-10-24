<?php
declare(strict_types=1);

namespace App\Models;

// Ժանր — PHP Դաս 12, տնային 3
class Genre
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self((int) $row['id'], $row['name'], $row['slug']);
    }

    public function url(): string
    {
        return '/genre/' . $this->slug;
    }
}
