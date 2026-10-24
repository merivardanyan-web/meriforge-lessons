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

// ================= Ադմին՝ CRUD — PHP Դաս 11 =================

/** Բոլոր գրքերը, ներառյալ թաքցվածները, որոնումով (տնային 8) */
function getAdminBooks(string $q = ''): array
{
    $sql = 'SELECT b.id, b.title, b.slug, b.author, b.pages, b.read_pages, b.is_active, g.name AS genre
            FROM books b
            LEFT JOIN genres g ON g.id = b.genre_id';
    $params = [];

    if ($q !== '') {
        $sql .= ' WHERE b.title LIKE :q OR b.author LIKE :q2';
        $params = ['q' => "%{$q}%", 'q2' => "%{$q}%"];
    }

    $stmt = db()->prepare($sql . ' ORDER BY b.is_active DESC, b.title');
    $stmt->execute($params);

    return $stmt->fetchAll();
}

/** Խմբագրման համար՝ նաև թաքցված գիրքը, genre_id-ով */
function getBookForAdmin(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM books WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    if ($row === false) {
        return null;
    }

    // ⚠️ Ֆորման արժեքները ետ է ուղարկում տողերով, իսկ select-ի համեմատությունը՝ int-ով
    $row['genre_id'] = $row['genre_id'] === null ? '' : (int) $row['genre_id'];

    return $row;
}

function uniqueSlug(string $base, ?int $exceptId = null): string
{
    $slug = $base;
    $i = 1;

    while (true) {
        $sql = 'SELECT COUNT(*) FROM books WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($exceptId !== null) {
            $sql .= ' AND id != :id';           // ⭐ առանց սրա խմբագրելիս slug-ը կաճեր՝ samvel-2
            $params['id'] = $exceptId;
        }

        $stmt = db()->prepare($sql);
        $stmt->execute($params);

        if ((int) $stmt->fetchColumn() === 0) {
            return $slug;
        }

        $slug = $base . '-' . (++$i);
    }
}

/** Ֆորմայի դաշտերը → սյունակներ։ Միայն թվարկվածները, ոչ ամբողջ $_POST-ը (mass assignment) */
function bookData(array $post, ?string $cover, ?int $exceptId = null): array
{
    $title = trim($post['title']);

    return [
        'title'      => $title,
        'slug'       => uniqueSlug(slugify($title), $exceptId),
        'author'     => trim($post['author']),
        'genre_id'   => ($post['genre_id'] ?? '') === '' ? null : (int) $post['genre_id'],
        'year'       => trim($post['year'] ?? '') === '' ? null : (int) $post['year'],
        'pages'      => (int) $post['pages'],
        'read_pages' => (int) ($post['read_pages'] ?? 0),
        'rating'     => (int) ($post['rating'] ?? 0),
        'color'      => $post['color'],
        'link'       => trim($post['link'] ?? '') ?: null,
        'cover'      => $cover,
        'is_active'  => isset($post['is_active']) ? 1 : 0,       // checkbox՝ չնշված → չկա $_POST-ում
    ];
}

function createBook(array $data): int
{
    $stmt = db()->prepare('
        INSERT INTO books (title, slug, author, genre_id, year, pages, read_pages, rating, color, link, cover, is_active)
        VALUES (:title, :slug, :author, :genre_id, :year, :pages, :read_pages, :rating, :color, :link, :cover, :is_active)
    ');
    $stmt->execute($data);

    return (int) db()->lastInsertId();
}

/** @return bool  փոխվե՞լ է որևէ բան (rowCount-ի թակարդը՝ տնային 7) */
function updateBook(int $id, array $data): bool
{
    $stmt = db()->prepare('
        UPDATE books SET
            title = :title, slug = :slug, author = :author, genre_id = :genre_id, year = :year,
            pages = :pages, read_pages = :read_pages, rating = :rating, color = :color,
            link = :link, cover = :cover, is_active = :is_active
        WHERE id = :id
    ');
    $data['id'] = $id;
    $stmt->execute($data);

    // ⚠️ 0 նշանակում է «ոչինչ չփոխվեց», ՈՉ «չգտնվեց»․ MySQL-ը չի հաշվում նույն արժեքով տողերը
    return $stmt->rowCount() > 0;
}

/** Զանգվածային թաքցում (տնային 9)․ ids[]=2&ids[]=5 */
function archiveBooks(array $ids): int
{
    $ids = array_values(array_filter(array_map('intval', $ids), fn(int $id): bool => $id > 0));

    if ($ids === []) {
        return 0;
    }

    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("UPDATE books SET is_active = 0 WHERE id IN ({$in})");
    $stmt->execute($ids);

    return $stmt->rowCount();
}

// ================= Ընթերցանության օրեր — PHP Դաս 11 =================

function getSessions(int $bookId): array
{
    $stmt = db()->prepare('
        SELECT read_on, pages, note
        FROM reading_sessions
        WHERE book_id = :id
        ORDER BY read_on DESC, id DESC
    ');
    $stmt->execute(['id' => $bookId]);

    return $stmt->fetchAll();
}

/**
 * Երկու փոփոխություն՝ մեկ ամբողջություն (ինչպես պատվեր + տողեր)․
 * INSERT reading_sessions + UPDATE books.read_pages։ Կամ երկուսն էլ, կամ ոչ մեկը
 */
function logReading(int $bookId, int $pages, string $date, ?string $note): void
{
    $pdo = db();
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('
            INSERT INTO reading_sessions (book_id, pages, read_on, note)
            VALUES (:book, :pages, :read_on, :note)
        ');
        $stmt->execute(['book' => $bookId, 'pages' => $pages, 'read_on' => $date, 'note' => $note]);

        // Պայմանը WHERE-ում․ եթե մեկ ուրիշ ներդիրից արդեն ավելացրել ես էջեր, չենք անցնի pages-ից
        $stmt = $pdo->prepare('
            UPDATE books
            SET read_pages = read_pages + :pages
            WHERE id = :id AND read_pages + :pages2 <= pages
        ');
        $stmt->execute(['pages' => $pages, 'pages2' => $pages, 'id' => $bookId]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('Էջերը շատ են․ գիրքն այդքան էջ չունի');
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();          // INSERT-ը նույնպես չեղարկվում է
        throw $e;                  // կանչողը կորոշի՝ ինչ ցույց տալ
    }
}
