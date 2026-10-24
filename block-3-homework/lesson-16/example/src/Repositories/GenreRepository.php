<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Genre;
use PDO;

class GenreRepository
{
    public function __construct(private PDO $pdo) {}

    /** @return Genre[] */
    public function all(): array
    {
        $rows = $this->pdo->query('SELECT id, name, slug FROM genres ORDER BY name')->fetchAll();

        return array_map(fn(array $row): Genre => Genre::fromRow($row), $rows);
    }

    public function findBySlug(string $slug): ?Genre
    {
        $stmt = $this->pdo->prepare('SELECT id, name, slug FROM genres WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        return $row === false ? null : Genre::fromRow($row);
    }

    /** @return int[] */
    public function ids(): array
    {
        return array_map(fn(Genre $g): int => $g->id, $this->all());
    }
}
