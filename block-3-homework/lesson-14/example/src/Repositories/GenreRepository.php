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

    /** @return int[] */
    public function ids(): array
    {
        return array_map(fn(Genre $g): int => $g->id, $this->all());
    }
}
