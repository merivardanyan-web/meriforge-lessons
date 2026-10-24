<?php
declare(strict_types=1);

// ⚠️ ՓՈՐՁՆԱԿԱՆ ֆայլ — PHP Դաս 14, տնային 2 և 7․ փորձից հետո ՋՆՋԻՐ
namespace Lab;

use App\Models\Genre;
use App\Repositories\GenreRepository;

require_once __DIR__ . '/vendor/autoload.php';
header('Content-Type: text/plain; charset=utf-8');

// ---------- Տնային 2 · առանց use PDO; ----------
echo "== new PDO() namespace Lab-ի ներսում, առանց use PDO;\n";
try {
    $pdo = new PDO('sqlite::memory:');          // PHP-ն փնտրում է Lab\PDO
} catch (\Error $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}
echo "→ Ուղղում․ use PDO; ֆայլի սկզբում կամ \\PDO\n\n";

// ---------- Տնային 7 · Repository-ն՝ փորձնական PDO-ով ----------
// SQLite-ը՝ հիշողության մեջ․ ոչ MySQL, ոչ սերվեր, ոչ ani_books բազա
$fake = new \PDO('sqlite::memory:', null, null, [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC]);
$fake->exec('CREATE TABLE genres (id INTEGER PRIMARY KEY, name TEXT, slug TEXT)');
$fake->exec("INSERT INTO genres (name, slug) VALUES ('փորձ', 'test'), ('ալֆա', 'alpha')");

$repo = new GenreRepository($fake);          // ⭐ նույն դասը, այլ PDO

echo "== GenreRepository SQLite-ով\n";
foreach ($repo->all() as $genre) {
    echo ($genre instanceof Genre ? 'Genre' : '?') . " #{$genre->id} · {$genre->name}\n";
}
echo "→ Repository-ն չգիտի՝ որտեղից է PDO-ն։ Այդպես են գրվում թեստերը՝ առանց իսկական բազայի\n";
