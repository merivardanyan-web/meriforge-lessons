<?php
declare(strict_types=1);

// Ապրանքների հարցումները — PHP Դաս 10, 11
// Շաբլոնները SQL չգիտեն, իսկ այս ֆայլը HTML չգիտի

const PER_PAGE = 6;

/** Սյունակի անունը չի պարամետրացվում, ուստի՝ սպիտակ ցուցակ — PHP Դաս 10 ⭐ */
const SORT_MAP = [
    'newest'     => ['Նորերը',               'p.created_at DESC'],
    'price_asc'  => ['Գինը՝ աճման կարգով',   'final_price ASC'],
    'price_desc' => ['Գինը՝ նվազման կարգով', 'final_price DESC'],
    'name'       => ['Անունով',              'p.name ASC'],
];

const STOCK_FILTERS = [
    'all'  => 'Բոլորը',
    'in'   => 'Միայն առկաները',
    'sale' => 'Միայն զեղչվածները',
];

/** Դաս 8-ի finalPrice()-ը՝ SQL-ում։ Կրկնվում է WHERE-ում, որովհետև այնտեղ final_price-ը դեռ չկա */
const FINAL_PRICE = 'ROUND(p.price * (100 - p.discount) / 100)';

const PRODUCT_FIELDS = '
    p.id, p.category_id, p.name, p.slug, p.specs, p.description, p.image,
    p.price, p.discount, p.stock, p.is_active, p.created_at,
    c.name AS category_name, c.slug AS category_slug,
    ' . FINAL_PRICE . ' AS final_price
';

/**
 * DECIMAL-ը PDO-ից գալիս է տող՝ "450000.00"։ strict_types-ով formatPrice()-ը
 * կգցեր TypeError, ուստի փոխարկում ենք մեկ տեղում — PHP Դաս 10-ի տնային
 */
function normalizeProduct(array $row): array
{
    foreach (['id', 'category_id', 'price', 'discount', 'stock', 'final_price'] as $key) {
        if (isset($row[$key])) {
            $row[$key] = (int) $row[$key];
        }
    }
    $row['is_active'] = (bool) ($row['is_active'] ?? true);

    return $row;
}

// ---------- Կատեգորիաներ ----------

function getCategories(): array
{
    static $categories = null;     // header-ը և ֆիլտրերը կանչում են նույն էջում՝ մեկ հարցում

    return $categories ??= db()
        ->query('SELECT id, name, slug FROM categories ORDER BY id')
        ->fetchAll();
}

function getCategoryBySlug(string $slug): ?array
{
    foreach (getCategories() as $category) {
        if ($category['slug'] === $slug) {
            return $category;
        }
    }

    return null;
}

// ---------- Կատալոգի ֆիլտրեր ----------

/** Ոչ բացասական ամբողջ թիվ URL-ից կամ null (դատարկ դաշտը ֆիլտր չէ) */
function queryInt(array $query, string $key): ?int
{
    $value = filter_var($query[$key] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

    return $value === false ? null : $value;      // ⭐ === false, որովհետև 0-ն վավեր է
}

/** URL-ի պարամետրերը → ստուգված ֆիլտրեր։ Անհայտ արժեքը դառնում է լռելյայն */
function readFilters(array $query): array
{
    $category = getCategoryBySlug((string) ($query['category'] ?? ''));
    $stock = (string) ($query['stock'] ?? 'all');

    return [
        'category'    => $category['slug'] ?? null,
        'category_id' => $category['id'] ?? null,
        'q'           => mb_substr(trim((string) ($query['q'] ?? '')), 0, 100),
        'min'         => queryInt($query, 'min'),
        'max'         => queryInt($query, 'max'),
        'stock'       => array_key_exists($stock, STOCK_FILTERS) ? $stock : 'all',
    ];
}

/** Դինամիկ WHERE — PHP Դաս 10-ի տնային։ Վերադարձնում է [SQL, պարամետրեր] */
function buildWhere(array $filters): array
{
    $where  = ['p.is_active = 1'];
    $params = [];

    if ($filters['category_id'] !== null) {
        $where[] = 'p.category_id = :category';
        $params['category'] = (int) $filters['category_id'];
    }

    if ($filters['q'] !== '') {
        // ⭐ %-ը արժեքի մեջ է, ոչ SQL-ում։ Իսկ օգտագործողի % և _ նշանները փախցնում ենք
        $where[] = '(p.name LIKE :q OR p.specs LIKE :q2)';
        $like = '%' . addcslashes($filters['q'], '%_\\') . '%';
        $params['q'] = $like;
        $params['q2'] = $like;
    }

    if ($filters['min'] !== null) {
        $where[] = FINAL_PRICE . ' >= :min';
        $params['min'] = $filters['min'];
    }

    if ($filters['max'] !== null) {
        $where[] = FINAL_PRICE . ' <= :max';
        $params['max'] = $filters['max'];
    }

    if ($filters['stock'] === 'in') {
        $where[] = 'p.stock > 0';
    } elseif ($filters['stock'] === 'sale') {
        $where[] = 'p.discount > 0';
    }

    return [implode(' AND ', $where), $params];
}

function bindAll(PDOStatement $stmt, array $params): void
{
    foreach ($params as $key => $value) {
        $stmt->bindValue(":{$key}", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
}

// ---------- Կարդալ ----------

function getProducts(array $filters, string $sort = 'newest', int $limit = PER_PAGE, int $offset = 0): array
{
    $order = (SORT_MAP[$sort] ?? SORT_MAP['newest'])[1];
    [$where, $params] = buildWhere($filters);

    $stmt = db()->prepare("
        SELECT " . PRODUCT_FIELDS . "
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE {$where}
        ORDER BY {$order}, p.id
        LIMIT :limit OFFSET :offset
    ");

    bindAll($stmt, $params);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);      // ⭐ LIMIT-ը՝ միայն INT — PHP Դաս 10
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return array_map('normalizeProduct', $stmt->fetchAll());
}

function countProducts(array $filters): int
{
    [$where, $params] = buildWhere($filters);

    $stmt = db()->prepare("SELECT COUNT(*) FROM products p WHERE {$where}");
    bindAll($stmt, $params);
    $stmt->execute();

    return (int) $stmt->fetchColumn();
}

function getProductBySlug(string $slug): ?array
{
    $stmt = db()->prepare("
        SELECT " . PRODUCT_FIELDS . "
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.slug = :slug AND p.is_active = 1
    ");
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();

    return $row ? normalizeProduct($row) : null;
}

/** Զամբյուղի համար — IN (?, ?, ?) — PHP Դաս 10։ Վերադարձնում է [id => ապրանք] */
function getProductsByIds(array $ids): array
{
    if ($ids === []) {
        return [];
    }

    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("
        SELECT " . PRODUCT_FIELDS . "
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.id IN ({$in}) AND p.is_active = 1
    ");
    $stmt->execute(array_values(array_map('intval', $ids)));

    $result = [];
    foreach ($stmt->fetchAll() as $row) {
        $result[(int) $row['id']] = normalizeProduct($row);
    }

    return $result;
}

/** Նույն կատեգորիայից, հետո մյուսները — Բլոկ 2-ի «Նման ապրանքներ» */
function getRelated(array $product, int $limit = 3): array
{
    $stmt = db()->prepare("
        SELECT " . PRODUCT_FIELDS . "
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.is_active = 1 AND p.id <> :id
        ORDER BY (p.category_id = :category) DESC, p.created_at DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':id', $product['id'], PDO::PARAM_INT);
    $stmt->bindValue(':category', $product['category_id'], PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return array_map('normalizeProduct', $stmt->fetchAll());
}

// ---------- Ադմին — PHP Դաս 11 ----------

/** Ադմինի ցուցակ՝ նաև անջատվածները, որոնումով — PHP Դաս 11-ի տնային */
function getAllProducts(string $q = ''): array
{
    $sql = "
        SELECT p.id, p.name, p.slug, p.price, p.discount, p.stock, p.is_active, p.image,
               c.name AS category_name, " . FINAL_PRICE . " AS final_price
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
    ";
    $params = [];

    if ($q !== '') {
        $sql .= ' WHERE p.name LIKE :q';
        $params['q'] = '%' . addcslashes($q, '%_\\') . '%';
    }

    $stmt = db()->prepare($sql . ' ORDER BY p.created_at DESC, p.id DESC');
    $stmt->execute($params);

    return array_map('normalizeProduct', $stmt->fetchAll());
}

/** Ադմինի համար՝ ԱՌԱՆՑ is_active ֆիլտրի */
function getProductForAdmin(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row ? normalizeProduct($row) : null;
}

function uniqueSlug(string $base, ?int $exceptId = null): string
{
    $slug = $base;
    $i = 1;

    while (true) {
        $sql = 'SELECT COUNT(*) FROM products WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($exceptId !== null) {
            $sql .= ' AND id != :id';           // ⭐ առանց սրա խմբագրելիս slug-ը կաճեր
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

/** Ֆորմայի դաշտերը → բազայի սյունակներ։ Միայն թվարկվածները, ոչ ամբողջ $_POST-ը */
function productData(array $post, ?string $image, ?int $exceptId = null): array
{
    $name = trim($post['name']);

    return [
        'category_id' => $post['category_id'] !== '' ? (int) $post['category_id'] : null,
        'name'        => $name,
        'slug'        => uniqueSlug(slugify($name), $exceptId),
        'specs'       => trim($post['specs'] ?? '') ?: null,
        'description' => trim($post['description'] ?? '') ?: null,
        'price'       => (int) $post['price'],
        'discount'    => (int) ($post['discount'] ?? 0),
        'stock'       => (int) ($post['stock'] ?? 0),
        'image'       => $image,
        'is_active'   => isset($post['is_active']) ? 1 : 0,
    ];
}

function createProduct(array $data): int
{
    $stmt = db()->prepare('
        INSERT INTO products
            (category_id, name, slug, specs, description, price, discount, stock, image, is_active)
        VALUES
            (:category_id, :name, :slug, :specs, :description, :price, :discount, :stock, :image, :is_active)
    ');
    $stmt->execute($data);

    return (int) db()->lastInsertId();
}

function updateProduct(int $id, array $data): void
{
    $stmt = db()->prepare('
        UPDATE products SET
            category_id = :category_id, name = :name, slug = :slug, specs = :specs,
            description = :description, price = :price, discount = :discount,
            stock = :stock, image = :image, is_active = :is_active
        WHERE id = :id
        LIMIT 1
    ');
    $stmt->execute($data + ['id' => $id]);
}

/** Փափուկ ջնջում մի քանի ապրանքի համար — PHP Դաս 11-ի տնային */
function archiveProducts(array $ids): int
{
    $ids = array_values(array_filter(array_map('intval', $ids), fn(int $id): bool => $id > 0));

    if ($ids === []) {
        return 0;
    }

    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("UPDATE products SET is_active = 0 WHERE id IN ({$in})");
    $stmt->execute($ids);

    return $stmt->rowCount();      // ⚠️ արդեն արխիվացվածները չեն հաշվվում — PHP Դաս 11
}
