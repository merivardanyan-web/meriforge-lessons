<?php
declare(strict_types=1);

// ⚠️ ՓՈՐՁՆԱԿԱՆ ֆայլ — PHP Դաս 11, տնային 4–7
// Աշխատում է lab_ աղյուսակների վրա, իրական գրքերին չի դիպչում։ Փորձից հետո ՋՆՋԻՐ
require_once __DIR__ . '/bootstrap.php';
header('Content-Type: text/plain; charset=utf-8');

$pdo = db();

function count_rows(string $table): int
{
    return (int) db()->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
}

// ---------- Տնային 4, 5 · տրանզակցիա InnoDB-ում և MyISAM-ում ----------
foreach (['InnoDB', 'MyISAM'] as $engine) {
    $table = 'lab_log_' . strtolower($engine);
    $pdo->exec("DROP TABLE IF EXISTS {$table}");
    $pdo->exec("CREATE TABLE {$table} (id INT AUTO_INCREMENT PRIMARY KEY, what VARCHAR(50)) ENGINE={$engine}");

    $pdo->beginTransaction();
    try {
        $pdo->exec("INSERT INTO {$table} (what) VALUES ('սեսիա')");
        $pdo->exec("INSERT INTO {$table} (what) VALUES ('read_pages')");
        throw new RuntimeException('Դիտմամբ սխալ երկրորդ INSERT-ից հետո');
    } catch (RuntimeException $e) {
        $pdo->rollBack();
    }

    echo "{$engine}: rollBack()-ից հետո աղյուսակում մնաց " . count_rows($table) . " տող\n";
    $pdo->exec("DROP TABLE {$table}");
}
echo "→ MyISAM-ը տրանզակցիա չունի․ rollBack()-ը ոչինչ չի չեղարկում\n\n";

// ---------- Տնային 6 · UPDATE առանց WHERE ----------
$pdo->exec('DROP TABLE IF EXISTS lab_books');
$pdo->exec('CREATE TABLE lab_books AS SELECT id, title, rating FROM books');
$changed = $pdo->exec('UPDATE lab_books SET rating = 1');          // ❌ WHERE չկա
echo "UPDATE առանց WHERE․ փոխվեց {$changed} տող՝ ամբողջ աղյուսակը\n";
echo "→ Միշտ նախ SELECT նույն WHERE-ով, հետո UPDATE\n\n";

// ---------- Տնային 7 · rowCount()-ի թակարդը ----------
$stmt = $pdo->prepare('UPDATE lab_books SET rating = :r WHERE id = :id');
$stmt->execute(['r' => 5, 'id' => 1]);
echo 'Առաջին անգամ rating = 5 · rowCount() = ' . $stmt->rowCount() . "\n";
$stmt->execute(['r' => 5, 'id' => 1]);
echo 'Նույն արժեքով կրկին  · rowCount() = ' . $stmt->rowCount() . "\n";
$stmt->execute(['r' => 5, 'id' => 999]);
echo 'Գոյություն չունեցող id · rowCount() = ' . $stmt->rowCount() . "\n";
echo "→ 0-ն չի տարբերում «չգտնվեց»-ը «ոչինչ չփոխվեց»-ից։ Գոյությունը ստուգիր SELECT-ով\n";

$pdo->exec('DROP TABLE lab_books');
