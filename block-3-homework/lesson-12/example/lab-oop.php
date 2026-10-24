<?php
declare(strict_types=1);

// ⚠️ ՓՈՐՁՆԱԿԱՆ ֆայլ — PHP Դաս 12, տնային 4–6․ փորձից հետո ՋՆՋԻՐ
require_once __DIR__ . '/bootstrap.php';
header('Content-Type: text/plain; charset=utf-8');

function attempt(string $title, callable $fn): void
{
    echo "== {$title}\n";
    try {
        $fn();
    } catch (Throwable $e) {
        echo get_class($e) . ': ' . $e->getMessage() . "\n";
    }
    echo "\n";
}

$row = db()->query("SELECT b.*, g.name AS genre FROM books b LEFT JOIN genres g ON g.id = b.genre_id WHERE b.id = 2")->fetch();

// Տնային 4 · տառասխալը զանգվածում և օբյեկտում
attempt("\$row['titel'] — զանգված", function () use ($row): void {
    $value = @$row['titel'];          // @-ը՝ միայն որ Warning-ը չխառնի ելքը
    echo 'Արդյունք՝ ' . var_export($value, true) . " — Warning, և կոդը շարունակում է null-ով\n";
});

attempt('$book->titel — օբյեկտ', function () use ($row): void {
    $book = Book::fromRow($row);
    echo 'Արդյունք՝ ' . var_export($book->titel, true) . "\n";     // Warning: Undefined property
});

attempt('$book->titel = ... — նոր դաշտ օբյեկտում', function () use ($row): void {
    $book = Book::fromRow($row);
    $book->titel = 'Սամվել';          // Deprecated (PHP 8.2+)՝ դինամիկ դաշտ
    echo "Գրվեց, բայց PHP 8.2-ը զգուշացնում է․ PHP 9-ում կլինի Error\n";
});

// Տնային 5 · readonly
attempt('$book->pages = 1 — readonly', function () use ($row): void {
    $book = Book::fromRow($row);
    $book->pages = 1;                 // Error: Cannot modify readonly property
});

// Տնային 6 · ReadingList առանց &-ի
class ReadingListCopy
{
    public function __construct(private array $storage) {}       // ❌ առանց &

    public function add(int $id): void
    {
        $this->storage[$id] = 20;
    }
}

attempt('ReadingList առանց &-ի', function (): void {
    $session = [];
    (new ReadingListCopy($session))->add(5);
    echo 'Առանց &․ $session = ' . json_encode($session) . "  ← ոչինչ չպահվեց\n";

    $session = [];
    (new ReadingList($session))->add(5);
    echo '&-ով․    $session = ' . json_encode($session) . "\n";
});
