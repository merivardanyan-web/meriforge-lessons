<?php
declare(strict_types=1);

// ⚠️ ՓՈՐՁՆԱԿԱՆ ֆայլ — PHP Դաս 10, տնային 4–7
// Չորս թակարդ։ Ամեն մեկը բռնում ենք try/catch-ով, որ էջը չընկնի և տեսնենք սխալը
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');

function attempt(string $title, callable $fn): void
{
    echo "== {$title}\n";
    try {
        echo $fn() . "\n\n";
    } catch (Throwable $e) {
        echo get_class($e) . ': ' . $e->getMessage() . "\n\n";
    }
}

// Տնային 4 · ORDER BY :field — պարամետրը դառնում է ՏՈՂ, MySQL-ը դասավորում է 'title' հաստատունով
attempt('ORDER BY :field', function (): string {
    $stmt = db()->prepare('SELECT title FROM books ORDER BY :field LIMIT 3');
    $stmt->execute(['field' => 'title']);
    $titles = array_map(fn(array $r): string => $r['title'], $stmt->fetchAll());
    return 'Սխալ չկա, բայց կարգը id-ի կարգն է, ոչ այբբենական․ ' . implode(', ', $titles);
});

// Տնային 5 · LIMIT execute([...])-ով — execute()-ը ամեն արժեք ուղարկում է ՏՈՂ։
// Emulated prepares-ով (PDO-ի լռելյայն ռեժիմը, եթե db.php-ում չանջատես) PHP-ն ինքն է տեղադրում
// արժեքը SQL-ում՝ չակերտներով → LIMIT '3' → սինտաքսի սխալ
function emulatedPdo(): PDO
{
    $config = require __DIR__ . '/config.php';
    $c = $config['db'];
    return new PDO("mysql:host={$c['host']};dbname={$c['name']};charset=utf8mb4", $c['user'], $c['password'], [
        PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => true,
    ]);
}

attempt('LIMIT execute([...])-ով (emulated prepares)', function (): string {
    $stmt = emulatedPdo()->prepare('SELECT title FROM books LIMIT :limit');
    $stmt->execute(['limit' => 3]);
    return count($stmt->fetchAll()) . ' տող';
});

attempt('LIMIT bindValue(..., PDO::PARAM_INT)-ով (emulated prepares)', function (): string {
    $stmt = emulatedPdo()->prepare('SELECT title FROM books LIMIT :limit');
    $stmt->bindValue(':limit', 3, PDO::PARAM_INT);
    $stmt->execute();
    return count($stmt->fetchAll()) . ' տող ✓';
});

// Տնային 6 · առանց ERRMODE_EXCEPTION — սխալ հարցումը լուռ վերադարձնում է false
attempt('ERRMODE_SILENT', function (): string {
    $config = require __DIR__ . '/config.php';
    $c = $config['db'];
    $pdo = new PDO("mysql:host={$c['host']};dbname={$c['name']};charset=utf8mb4", $c['user'], $c['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    ]);
    $result = $pdo->query('SELECT titel FROM books');             // տառասխալ սյունակում
    return 'query() վերադարձրեց ' . var_export($result, true) . '․ ոչ մի սխալ էկրանին, կոդը շարունակում է «դատարկ» տվյալներով';
});

// Տնային 7 · ROUND()/AVG()/SUM()-ը PDO-ից գալիս են ՏՈՂ, իսկ strict_types-ը տող չի ընդունում int-ի տեղ
attempt('AVG(pages) → formatPages(int)', function (): string {
    $avg = db()->query('SELECT AVG(pages) FROM books')->fetchColumn();
    echo 'AVG = ' . var_export($avg, true) . "\n";
    return formatPages($avg);                                     // TypeError
});

attempt('Ուղղված․ (int)', function (): string {
    $avg = db()->query('SELECT AVG(pages) FROM books')->fetchColumn();
    return formatPages((int) $avg) . ' ✓';
});
