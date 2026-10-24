<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Book;
use PDO;
use PDOStatement;

/**
 * Ամբողջ SQL-ը գրքերի մասին՝ մեկ դասում — PHP Դաս 14 (Դաս 10–11-ի books.php-ն)
 * PDO-ն գալիս է դրսից (կոնստրուկտորով)․ դասը չգիտի՝ MySQL է, թե փորձնական SQLite (տնային 7)
 */
class BookRepository
{
    public const PER_PAGE = 6;

    /** ORDER BY-ը չի պարամետրացվում → սպիտակ ցուցակ (Դաս 10) */
    public const SORTS = [
        'progress' => ['Առաջընթաց',    'progress DESC, b.title ASC'],
        'title'    => ['Այբբենական',   'b.title ASC'],
        'newest'   => ['Նորերը',       'b.created_at DESC'],
        'pages'    => ['Հաստից բարակ', 'b.pages DESC'],
    ];

    public const STATUS_SQL = [
        'planned' => 'b.read_pages = 0',
        'reading' => 'b.read_pages > 0 AND b.read_pages < b.pages',
        'done'    => 'b.read_pages >= b.pages',
    ];

    /** Նոր սյունակ՝ մեկ տեղում, և բոլոր հարցումները այն ստանում են (տնային 6) */
    private const SELECT_FIELDS = '
        b.id, b.title, b.slug, b.author, b.year, b.pages, b.read_pages, b.rating,
        b.format, b.minutes, b.narrator, b.lent_to,
        b.color, b.link, b.cover, b.created_at, g.name AS genre, g.slug AS genre_slug,
        ROUND(b.read_pages * 100 / b.pages) AS progress
    ';

    private const FROM = 'FROM books b LEFT JOIN genres g ON g.id = b.genre_id';

    public function __construct(private PDO $pdo) {}

    // ================= Կայք =================

    /** Ֆիլտրերը URL-ից՝ ստուգված։ Անծանոթ արժեքը՝ null կամ '', ոչ սխալ */
    public static function filtersFrom(array $get): array
    {
        $min = filter_var($get['min'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        $max = filter_var($get['max'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

        return [
            'q'      => trim((string) ($get['q'] ?? '')),
            'genre'  => (string) ($get['genre'] ?? ''),
            'status' => isset(self::STATUS_SQL[$get['status'] ?? '']) ? $get['status'] : '',
            'min'    => $min === false ? null : $min,
            'max'    => $max === false ? null : $max,
        ];
    }

    /** @return Book[] */
    public function search(array $filters, string $sort = 'progress', int $limit = self::PER_PAGE, int $offset = 0): array
    {
        [$where, $params] = $this->where($filters);
        $order = (self::SORTS[$sort] ?? self::SORTS['progress'])[1];

        $stmt = $this->pdo->prepare(
            'SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . "
             WHERE {$where}
             ORDER BY {$order}
             LIMIT :limit OFFSET :offset"
        );

        $this->bind($stmt, $params);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrate($stmt);
    }

    public function count(array $filters): int
    {
        [$where, $params] = $this->where($filters);

        $stmt = $this->pdo->prepare('SELECT COUNT(*) ' . self::FROM . " WHERE {$where}");
        $this->bind($stmt, $params);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function find(int $id): ?Book
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . ' WHERE b.id = :id AND b.is_active = 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : Book::fromRow($row);
    }

    /** /book/samvel — PHP Դաս 15 */
    public function findBySlug(string $slug): ?Book
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . ' WHERE b.slug = :slug AND b.is_active = 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        return $row === false ? null : Book::fromRow($row);
    }

    /** @return Book[] */
    public function findMany(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare('SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . " WHERE b.id IN ({$in}) AND b.is_active = 1");
        $stmt->execute($ids);

        return $this->hydrate($stmt);
    }

    public function stats(): array
    {
        $none = self::filtersFrom([]);
        $done = $none;
        $done['status'] = 'done';

        $sum = $this->pdo->query('SELECT SUM(read_pages) FROM books WHERE is_active = 1')->fetchColumn();

        return [
            'books'      => $this->count($none),
            'done'       => $this->count($done),
            'read_pages' => (int) $sum,
        ];
    }

    public function saveCover(int $id, string $file): void
    {
        $stmt = $this->pdo->prepare('UPDATE books SET cover = :cover WHERE id = :id');
        $stmt->execute(['cover' => $file, 'id' => $id]);
    }

    // ================= Ադմին =================

    public function adminList(string $q = ''): array
    {
        $sql = 'SELECT b.id, b.title, b.slug, b.author, b.pages, b.read_pages, b.is_active, b.format, g.name AS genre ' . self::FROM;
        $params = [];

        if ($q !== '') {
            $sql .= ' WHERE b.title LIKE :q OR b.author LIKE :q2';
            $params = ['q' => "%{$q}%", 'q2' => "%{$q}%"];
        }

        $stmt = $this->pdo->prepare($sql . ' ORDER BY b.is_active DESC, b.title');
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function findForAdmin(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        $row['genre_id'] = $row['genre_id'] === null ? '' : (int) $row['genre_id'];

        return $row;
    }

    /** Ֆորմայի դաշտերը → սյունակներ։ Միայն թվարկվածները (mass assignment) */
    public function dataFrom(array $post, ?string $cover, ?int $exceptId = null): array
    {
        $title  = trim($post['title']);
        $format = $post['format'];

        return [
            'title'      => $title,
            'slug'       => $this->uniqueSlug(slugify($title), $exceptId),
            'author'     => trim($post['author']),
            'genre_id'   => ($post['genre_id'] ?? '') === '' ? null : (int) $post['genre_id'],
            'year'       => trim($post['year'] ?? '') === '' ? null : (int) $post['year'],
            'pages'      => (int) $post['pages'],
            'read_pages' => (int) ($post['read_pages'] ?? 0),
            'rating'     => (int) ($post['rating'] ?? 0),
            'color'      => $post['color'],
            'link'       => trim($post['link'] ?? '') ?: null,
            'format'     => $format,
            'minutes'    => $format === 'audio' ? (int) $post['minutes'] : null,
            'narrator'   => $format === 'audio' ? (trim($post['narrator'] ?? '') ?: null) : null,
            'lent_to'    => $format === 'paper' ? (trim($post['lent_to'] ?? '') ?: null) : null,
            'cover'      => $cover,
            'is_active'  => isset($post['is_active']) ? 1 : 0,
        ];
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO books (title, slug, author, genre_id, year, pages, read_pages, rating, color, link,
                               format, minutes, narrator, lent_to, cover, is_active)
            VALUES (:title, :slug, :author, :genre_id, :year, :pages, :read_pages, :rating, :color, :link,
                    :format, :minutes, :narrator, :lent_to, :cover, :is_active)
        ');
        $stmt->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    /** @return bool  փոխվե՞լ է որևէ բան (rowCount, Դաս 11) */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE books SET
                title = :title, slug = :slug, author = :author, genre_id = :genre_id, year = :year,
                pages = :pages, read_pages = :read_pages, rating = :rating, color = :color,
                link = :link, format = :format, minutes = :minutes, narrator = :narrator, lent_to = :lent_to,
                cover = :cover, is_active = :is_active
            WHERE id = :id
        ');
        $data['id'] = $id;
        $stmt->execute($data);

        return $stmt->rowCount() > 0;
    }

    public function archive(array $ids): int
    {
        $ids = array_values(array_filter(array_map('intval', $ids), fn(int $id): bool => $id > 0));

        if ($ids === []) {
            return 0;
        }

        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare("UPDATE books SET is_active = 0 WHERE id IN ({$in})");
        $stmt->execute($ids);

        return $stmt->rowCount();
    }

    public function uniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = $base;
        $i = 1;

        while (true) {
            $sql = 'SELECT COUNT(*) FROM books WHERE slug = :slug';
            $params = ['slug' => $slug];

            if ($exceptId !== null) {
                $sql .= ' AND id != :id';
                $params['id'] = $exceptId;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            if ((int) $stmt->fetchColumn() === 0) {
                return $slug;
            }

            $slug = $base . '-' . (++$i);
        }
    }

    // ================= Ներքին =================

    /** @return array{0: string, 1: array} */
    private function where(array $f): array
    {
        $where  = ['b.is_active = 1'];
        $params = [];

        if ($f['q'] !== '') {
            $where[] = '(b.title LIKE :q OR b.author LIKE :q2)';
            $params['q']  = '%' . $f['q'] . '%';
            $params['q2'] = '%' . $f['q'] . '%';
        }

        if ($f['genre'] !== '') {
            $where[] = 'g.slug = :genre';
            $params['genre'] = $f['genre'];
        }

        if ($f['status'] !== '') {
            $where[] = self::STATUS_SQL[$f['status']];
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

    private function bind(PDOStatement $stmt, array $params): void
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
    }

    /** @return Book[] */
    private function hydrate(PDOStatement $stmt): array
    {
        return array_map(fn(array $row): Book => Book::fromRow($row), $stmt->fetchAll());
    }
}
