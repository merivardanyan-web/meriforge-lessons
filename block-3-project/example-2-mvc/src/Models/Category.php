<?php
declare(strict_types=1);

namespace App\Models;

/** Կատեգորիա — PHP Դաս 12-ի տնային */
class Category
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self((int) $row['id'], (string) $row['name'], (string) $row['slug']);
    }

    /** products-ի JOIN-ից՝ category_name, category_slug սյունակներով։ Առանց կատեգորիայի՝ null */
    public static function fromJoinedRow(array $row): ?self
    {
        if (empty($row['category_id']) || !isset($row['category_name'])) {
            return null;
        }

        return new self((int) $row['category_id'], (string) $row['category_name'], (string) ($row['category_slug'] ?? ''));
    }

    public function url(): string
    {
        return '/category/' . $this->slug;
    }
}
