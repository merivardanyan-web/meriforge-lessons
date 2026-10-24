<?php
declare(strict_types=1);

// Գրքերի հարցումները՝ մեկ ֆայլում — PHP Դաս 10
// Էջերը SQL չեն գրում․ կանչում են այս ֆունկցիաները

// ORDER BY-ը չի պարամետրացվում (:field-ը կդառնա տող 'title')․ միայն սպիտակ ցուցակ ⭐
const SORT_MAP = [
    'progress' => 'progress DESC, b.title ASC',
    'title'    => 'b.title ASC',
    'newest'   => 'b.created_at DESC',
    'pages'    => 'b.pages DESC',
];

const SORT_LABELS = [
    'progress' => 'Առաջընթաց',
    'title'    => 'Այբբենական',
    'newest'   => 'Նորերը',
    'pages'    => 'Հաստից բարակ',
];

// Կարգավիճակը՝ SQL պայման։ Նույն տրամաբանությունը, ինչ bookStatus()-ում
const STATUS_SQL = [
    'planned' => 'b.read_pages = 0',
    'reading' => 'b.read_pages > 0 AND b.read_pages < b.pages',
    'done'    => 'b.read_pages >= b.pages',
];

const BOOK_FIELDS = '
    b.id, b.title, b.slug, b.author, b.year, b.pages, b.read_pages, b.rating,
    b.color, b.link, b.cover, b.created_at, g.name AS genre, g.slug AS genre_slug,
    ROUND(b.read_pages * 100 / b.pages) AS progress
';

/** Բազայից եկած տողը → նույն զանգվածը, ինչ data.php-ում էր (Դաս 02–07-ի շաբլոնները աշխատում են) */
function normalizeBook(array $row): array
{
    $row['id']    = (int) $row['id'];
    $row['pages'] = (int) $row['pages'];
    $row['read']  = (int) $row['read_pages'];          // շաբլոնները կարդում են 'read'
    $row['rating'] = (int) $row['rating'];
    $row['year']  = $row['year'] === null ? null : (int) $row['year'];
    $row['genre'] = $row['genre'] ?? 'առանց ժանրի';    // LEFT JOIN․ ժանրը կարող է ջնջված լինել

    return $row;
}

/** Ֆիլտրերը URL-ից՝ ստուգված։ Անծանոթ արժեքը՝ null, ոչ սխալ */
function readFilters(array $get): array
{
    $min = filter_var($get['min'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    $max = filter_var($get['max'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

    return [
        'q'      => trim((string) ($get['q'] ?? '')),
        'genre'  => (string) ($get['genre'] ?? ''),
        'status' => isset(STATUS_SQL[$get['status'] ?? '']) ? $get['status'] : '',
        'min'    => $min === false ? null : $min,
        'max'    => $max === false ? null : $max,
    ];
}

/**
 * Դինամիկ WHERE (տնային 8, 9)․ ամեն ֆիլտր ավելացնում է պայման և պարամետր
 * @return array{0: string, 1: array}
 */
function buildWhere(array $f): array
{
    $where  = ['b.is_active = 1'];
    $params = [];

    if ($f['q'] !== '') {
        // % -ը՝ ԱՐԺԵՔՈՒՄ, ոչ SQL-ում։ Նույն պարամետրը երկու անգամ չի կարելի՝ :q և :q2
        $where[] = '(b.title LIKE :q OR b.author LIKE :q2)';
        $params['q']  = '%' . $f['q'] . '%';
        $params['q2'] = '%' . $f['q'] . '%';
    }

    if ($f['genre'] !== '') {
        $where[] = 'g.slug = :genre';
        $params['genre'] = $f['genre'];
    }

    if ($f['status'] !== '') {
        $where[] = STATUS_SQL[$f['status']];        // ⭐ մեր տեքստը, ոչ օգտագործողի
    }

    if ($f['min'] !== null) {
        $where[] = 'b.pages >= :min';
        $params['min'] = $f['min'];
    }

    if ($f['max'] !== null) {
        $where[] = 'b.pages <= :max';
        $params['max'] = $f['max'];
    }

    return [implode(' AND ', $where), $params];
}

function getBooks(array $filters, string $sort = 'progress', int $limit = 6, int $offset = 0): array
{
    [$where, $params] = buildWhere($filters);
    $order = SORT_MAP[$sort] ?? SORT_MAP['progress'];

    $stmt = db()->prepare('
        SELECT ' . BOOK_FIELDS . '
        FROM books b
        LEFT JOIN genres g ON g.id = b.genre_id
        WHERE ' . $where . '
        ORDER BY ' . $order . '
        LIMIT :limit OFFSET :offset
    ');

    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // LIMIT-ը՝ միայն PARAM_INT-ով։ execute([...])-ը բոլորը տող է դարձնում → LIMIT '6' (տնային 5)
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return array_map('normalizeBook', $stmt->fetchAll());
}

function countBooks(array $filters): int
{
    [$where, $params] = buildWhere($filters);

    $stmt = db()->prepare('
        SELECT COUNT(*)
        FROM books b
        LEFT JOIN genres g ON g.id = b.genre_id
        WHERE ' . $where);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
}

function getBook(int $id): ?array
{
    $stmt = db()->prepare('
        SELECT ' . BOOK_FIELDS . '
        FROM books b
        LEFT JOIN genres g ON g.id = b.genre_id
        WHERE b.id = :id AND b.is_active = 1
    ');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row === false ? null : normalizeBook($row);     // fetch()-ը չգտնելիս false է, ոչ null
}

/** Մի քանի գիրք id-ներով՝ կարդալու ցուցակի համար։ IN (?, ?, ?)՝ ըստ քանակի */
function getBooksByIds(array $ids): array
{
    if ($ids === []) {
        return [];                                 // IN ()-ը SQL սխալ է
    }

    $in = implode(',', array_fill(0, count($ids), '?'));

    $stmt = db()->prepare('
        SELECT ' . BOOK_FIELDS . '
        FROM books b
        LEFT JOIN genres g ON g.id = b.genre_id
        WHERE b.id IN (' . $in . ') AND b.is_active = 1
    ');
    $stmt->execute($ids);

    return array_map('normalizeBook', $stmt->fetchAll());
}

function getGenres(): array
{
    return db()
        ->query('SELECT id, name, slug FROM genres ORDER BY name')     // պարամետր չկա → query()
        ->fetchAll();
}

/** Գլխավոր էջի վերնագրի թվերը */
function getStats(): array
{
    $none = readFilters([]);
    $done = $none;
    $done['status'] = 'done';

    $sum = db()->query('SELECT SUM(read_pages) FROM books WHERE is_active = 1')->fetchColumn();

    // ⚠️ SUM()-ը PDO-ից գալիս է ՏՈՂ ("1052"), իսկ formatPages()-ը int է ուզում (strict_types, տնային 7)
    return [
        'books'      => countBooks($none),
        'done'       => countBooks($done),
        'read_pages' => (int) $sum,
    ];
}

function saveCover(int $id, string $file): void
{
    $stmt = db()->prepare('UPDATE books SET cover = :cover WHERE id = :id');
    $stmt->execute(['cover' => $file, 'id' => $id]);
}
