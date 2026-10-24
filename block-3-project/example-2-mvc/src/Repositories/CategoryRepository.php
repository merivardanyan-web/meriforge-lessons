<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use PDO;

class CategoryRepository
{
    private ?array $cache = null;

    public function __construct(private PDO $pdo) {}

    /** @return Category[]  մեկ հարցում մեկ էջի համար, թեև կանչում են header-ը և ֆիլտրերը */
    public function all(): array
    {
        return $this->cache ??= array_map(
            fn(array $row): Category => Category::fromRow($row),
            $this->pdo->query('SELECT id, name, slug FROM categories ORDER BY id')->fetchAll()
        );
    }

    public function findBySlug(string $slug): ?Category
    {
        foreach ($this->all() as $category) {
            if ($category->slug === $slug) {
                return $category;
            }
        }

        return null;
    }

    public function exists(int $id): bool
    {
        foreach ($this->all() as $category) {
            if ($category->id === $id) {
                return true;
            }
        }

        return false;
    }
}
