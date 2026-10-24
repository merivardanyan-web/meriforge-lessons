<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use PDO;
use PDOStatement;

/** Ամբողջ SQL-ը ապրանքների մասին՝ մեկ դասում — PHP Դաս 14 */
class ProductRepository
{
    public const PER_PAGE = 6;

    /** Սյունակի անունը չի պարամետրացվում → սպիտակ ցուցակ — PHP Դաս 10 */
    public const SORTS = [
        'newest'     => ['Նորերը',               'p.created_at DESC'],
        'price_asc'  => ['Գինը՝ աճման կարգով',   'final_price ASC'],
        'price_desc' => ['Գինը՝ նվազման կարգով', 'final_price DESC'],
        'name'       => ['Անունով',              'p.name ASC'],
    ];

    public const STOCK = [
        'all'  => 'Բոլորը',
        'in'   => 'Միայն առկաները',
        'sale' => 'Միայն զեղչվածները',
    ];

    private const FINAL_PRICE = 'ROUND(p.price * (100 - p.discount) / 100)';

    /** Նոր դաշտ՝ մեկ տեղում, և բոլոր հարցումները այն ստանում են — PHP Դաս 14-ի տնային */
    private const SELECT_FIELDS = '
        p.id, p.category_id, p.name, p.slug, p.specs, p.description, p.image,
        p.price, p.discount, p.stock, p.weight, p.type, p.is_active, p.created_at,
        c.name AS category_name, c.slug AS category_slug,
        ROUND(p.price * (100 - p.discount) / 100) AS final_price
    ';

    private const FROM = 'FROM products p LEFT JOIN categories c ON c.id = p.category_id';

    public function __construct(private PDO $pdo) {}

    // ---------- Կատալոգ ----------

    /**
     * @param array{category_id: ?int, q: string, min: ?int, max: ?int, stock: string} $filters
     * @return Product[]
     */
    public function search(array $filters, string $sort = 'newest', int $limit = self::PER_PAGE, int $offset = 0): array
    {
        $order = (self::SORTS[$sort] ?? self::SORTS['newest'])[1];
        [$where, $params] = $this->where($filters);

        $stmt = $this->pdo->prepare(
            'SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . "
             WHERE {$where}
             ORDER BY {$order}, p.id
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

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products p WHERE {$where}");
        $this->bind($stmt, $params);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public static function noFilters(): array
    {
        return ['category_id' => null, 'q' => '', 'min' => null, 'max' => null, 'stock' => 'all'];
    }

    // ---------- Մեկ ապրանք ----------

    public function find(int $id): ?Product
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . ' WHERE p.id = :id AND p.is_active = 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Product::fromRow($row) : null;
    }

    public function findBySlug(string $slug): ?Product
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . ' WHERE p.slug = :slug AND p.is_active = 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        return $row ? Product::fromRow($row) : null;
    }

    /** @param int[] $ids  @return array<int, Product>  բանալին՝ id */
    public function findMany(array $ids): array
    {
        $ids = array_values(array_map('intval', $ids));

        if ($ids === []) {
            return [];
        }

        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare('SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . " WHERE p.id IN ({$in}) AND p.is_active = 1");
        $stmt->execute($ids);

        $result = [];
        foreach ($this->hydrate($stmt) as $product) {
            $result[$product->id] = $product;
        }

        return $result;
    }

    /** @return Product[] */
    public function related(Product $product, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM . '
             WHERE p.is_active = 1 AND p.id <> :id
             ORDER BY (p.category_id = :category) DESC, p.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':id', $product->id, PDO::PARAM_INT);
        $stmt->bindValue(':category', $product->category?->id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrate($stmt);
    }

    // ---------- Ադմին ----------

    /** Նաև անջատվածները — PHP Դաս 11  @return Product[] */
    public function allForAdmin(string $q = ''): array
    {
        $sql = 'SELECT ' . self::SELECT_FIELDS . ' ' . self::FROM;
        $params = [];

        if ($q !== '') {
            $sql .= ' WHERE p.name LIKE :q';
            $params['q'] = '%' . addcslashes($q, '%_\\') . '%';
        }

        $stmt = $this->pdo->prepare($sql . ' ORDER BY p.created_at DESC, p.id DESC');
        $stmt->execute($params);

        return $this->hydrate($stmt);
    }

    /** Ֆորմայի համար՝ զանգված, առանց is_active ֆիլտրի */
    public function findRowForAdmin(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        // ⭐ DECIMAL-ը գալիս է "450000.00"․ ֆորմայում թողնենք 450000, թե չէ FILTER_VALIDATE_INT-ը կմերժի
        $row['price'] = (int) $row['price'];

        return $row;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO products
                (category_id, name, slug, specs, description, price, discount, stock, type, weight, image, is_active)
            VALUES
                (:category_id, :name, :slug, :specs, :description, :price, :discount, :stock, :type, :weight, :image, :is_active)
        ');
        $stmt->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare('
            UPDATE products SET
                category_id = :category_id, name = :name, slug = :slug, specs = :specs,
                description = :description, price = :price, discount = :discount, stock = :stock,
                type = :type, weight = :weight, image = :image, is_active = :is_active
            WHERE id = :id
            LIMIT 1
        ');
        $stmt->execute($data + ['id' => $id]);
    }

    /** @param int[] $ids */
    public function archive(array $ids): int
    {
        $ids = array_values(array_filter(array_map('intval', $ids), fn(int $id): bool => $id > 0));

        if ($ids === []) {
            return 0;
        }

        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare("UPDATE products SET is_active = 0 WHERE id IN ({$in})");
        $stmt->execute($ids);

        return $stmt->rowCount();
    }

    public function uniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = $base;
        $i = 1;

        while (true) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM products WHERE slug = :slug AND id <> :id');
            $stmt->execute(['slug' => $slug, 'id' => $exceptId ?? 0]);

            if ((int) $stmt->fetchColumn() === 0) {
                return $slug;
            }

            $slug = $base . '-' . (++$i);
        }
    }

    // ---------- Ներքին ----------

    /** Դինամիկ WHERE — PHP Դաս 10-ի տնային */
    private function where(array $filters): array
    {
        $where  = ['p.is_active = 1'];
        $params = [];

        if ($filters['category_id'] !== null) {
            $where[] = 'p.category_id = :category';
            $params['category'] = $filters['category_id'];
        }

        if ($filters['q'] !== '') {
            $where[] = '(p.name LIKE :q OR p.specs LIKE :q2)';
            $like = '%' . addcslashes($filters['q'], '%_\\') . '%';
            $params['q'] = $like;
            $params['q2'] = $like;
        }

        if ($filters['min'] !== null) {
            $where[] = self::FINAL_PRICE . ' >= :min';
            $params['min'] = $filters['min'];
        }

        if ($filters['max'] !== null) {
            $where[] = self::FINAL_PRICE . ' <= :max';
            $params['max'] = $filters['max'];
        }

        if ($filters['stock'] === 'in') {
            $where[] = "(p.stock > 0 OR p.type = 'digital')";
        } elseif ($filters['stock'] === 'sale') {
            $where[] = 'p.discount > 0';
        }

        return [implode(' AND ', $where), $params];
    }

    private function bind(PDOStatement $stmt, array $params): void
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
    }

    /** @return Product[] */
    private function hydrate(PDOStatement $stmt): array
    {
        return array_map(fn(array $row): Product => Product::fromRow($row), $stmt->fetchAll());
    }
}
