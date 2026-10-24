<?php
declare(strict_types=1);

// ⚠️ ՓՈՐՁՆԱԿԱՆ ֆայլ — PHP Դաս 17, տնային 4, 5, 6․ փորձից հետո ՋՆՋԻՐ
require_once __DIR__ . '/../bootstrap.php';

use App\Models\Book;
use App\Repositories\BookRepository;

// ---------- Տնային 4 · $_POST JSON-ի դեպքում ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? '') . "\n\$_POST = ";
    var_dump($_POST);
    echo "php://input = " . file_get_contents('php://input') . "\n";
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

// ---------- Տնային 5 · json_encode(օբյեկտ) ----------
$user = $users->findByEmail('ani@example.com');
echo "== json_encode(\$user)՝ ամեն public դաշտ, ՆԱԵՎ հեշը ❌\n";
echo json_encode($user, JSON_UNESCAPED_UNICODE) . "\n\n";

$book = $books->findBySlug('samvel');
echo "== json_encode(\$book) և \$book->toArray()\n";
echo json_encode($book, JSON_UNESCAPED_UNICODE) . "\n";
echo json_encode($book->toArray(), JSON_UNESCAPED_UNICODE) . "\n\n";

// ---------- Տնային 6 · array_filter-ի բանալիները ----------
$all = $books->search(BookRepository::filtersFrom([]), 'title', 6);
$unfinished = array_filter($all, fn(Book $b): bool => $b->status() !== 'done');
echo "== array_filter → բանալիները՝ " . implode(', ', array_keys($unfinished)) . "\n";
echo substr(json_encode(array_map(fn(Book $b): string => $b->title, $unfinished), JSON_UNESCAPED_UNICODE), 0, 80) . "…  ← օբյեկտ {}\n";
echo substr(json_encode(array_values(array_map(fn(Book $b): string => $b->title, $unfinished)), JSON_UNESCAPED_UNICODE), 0, 80) . "…  ← զանգված [] (array_values)\n";
