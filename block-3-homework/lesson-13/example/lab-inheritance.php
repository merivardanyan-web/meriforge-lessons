<?php
declare(strict_types=1);

// ⚠️ ՓՈՐՁՆԱԿԱՆ ֆայլ — PHP Դաս 13, տնային 3, 6, 7․ փորձից հետո ՋՆՋԻՐ
require_once __DIR__ . '/bootstrap.php';
header('Content-Type: text/plain; charset=utf-8');

// ---------- Տնային 3 · մոռացված parent::__construct() ----------
class BrokenAudioBook extends Book
{
    public function __construct(int $id, string $title, public readonly int $minutes = 0)
    {
        // parent::__construct(...) ՉԿԱ
    }

    public function formatLabel(): string { return 'Աուդիո'; }
    public function minutesLeft(): int { return $this->minutes; }
}

echo "== Առանց parent::__construct()\n";
$broken = new BrokenAudioBook(99, 'Փորձ', 30);
try {
    echo $broken->title . "\n";
} catch (Error $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}
echo "→ Օբյեկտը ստեղծվեց, բայց ծնողի դաշտերը չլրացվեցին։ Սխալը՝ առաջին կարդալիս, ոչ ստեղծելիս\n\n";

// ---------- Տնային 6 · մեկ foreach, երեք տեսակ ----------
$rows = db()->query("
    SELECT b.*, g.name AS genre FROM books b LEFT JOIN genres g ON g.id = b.genre_id
    WHERE b.slug IN ('samvel', 'hobity', '1984')
")->fetchAll();
$books = array_map(fn(array $r): Book => Book::fromRow($r), $rows);

echo "== Պոլիմորֆիզմ\n";
foreach ($books as $book) {
    echo str_pad(get_class($book), 10) . ' · ' . $book->formatLabel() . ' · մնաց ' . $book->timeLeftLabel() . "\n";
}
echo "\n";

// ---------- Տնային 7 · նույնը առանց պոլիմորֆիզմի ----------
echo "== if/elseif-ով (ամեն նոր տեսակ = ամեն այսպիսի տեղում նոր ճյուղ)\n";
foreach ($rows as $row) {
    if ($row['format'] === 'audio') {
        $label = '🎧 Աուդիո';
        $left  = (int) ($row['minutes'] * (100 - (int) ($row['read_pages'] * 100 / $row['pages'])) / 100);
    } elseif ($row['format'] === 'ebook') {
        $label = '💻 Էլեկտրոնային';
        $left  = ($row['pages'] - $row['read_pages']) * 2;
    } else {
        $label = '📖 Թղթային';
        $left  = ($row['pages'] - $row['read_pages']) * 2;
    }
    echo $label . ' · մնաց ' . (new DurationFormatter())->format($left) . "\n";
}
