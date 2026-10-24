<?php
declare(strict_types=1);

// Պատվերներ — PHP Դաս 9, 11

const ORDER_STATUS = [
    'new'       => 'Նոր',
    'paid'      => 'Վճարված',
    'shipped'   => 'Ճանապարհին',
    'cancelled' => 'Չեղարկված',
];

/**
 * Պատվեր՝ ամեն ինչ կամ ոչինչ — PHP Դաս 11
 * Եթե որևէ ապրանք սպառվել է, ոչ պատվերը, ոչ տողերը, ոչ պահեստը չեն փոխվում
 */
function createOrder(array $customer, array $items, array $summary): int
{
    $pdo = db();
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('
            INSERT INTO orders
                (customer_name, email, phone, delivery, region, address, payment, comment, shipping, total)
            VALUES
                (:name, :email, :phone, :delivery, :region, :address, :payment, :comment, :shipping, :total)
        ');

        $isPickup = $customer['delivery'] === 'pickup';

        $stmt->execute([
            'name'     => trim($customer['name']),
            'email'    => trim($customer['email'] ?? '') ?: null,
            'phone'    => trim($customer['phone']),
            'delivery' => $customer['delivery'],
            'region'   => $isPickup ? null : $customer['region'],
            'address'  => $isPickup ? null : trim($customer['address']),
            'payment'  => $customer['payment'],
            'comment'  => trim($customer['comment'] ?? '') ?: null,
            'shipping' => $summary['shipping'],
            'total'    => $summary['total'],
        ]);

        $orderId = (int) $pdo->lastInsertId();

        $itemStmt = $pdo->prepare('
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (:order_id, :product_id, :quantity, :price)
        ');

        // ⭐ stock >= :qty՝ նույն հարցումում, որ երկու գնորդ չգնեն վերջին ապրանքը միաժամանակ
        $stockStmt = $pdo->prepare('
            UPDATE products SET stock = stock - :qty
            WHERE id = :id AND is_active = 1 AND stock >= :qty2
        ');

        foreach ($items as $item) {
            $itemStmt->execute([
                'order_id'   => $orderId,
                'product_id' => $item['product']['id'],
                'quantity'   => $item['qty'],
                'price'      => $item['product']['final_price'],   // սառեցված գին — Դաս 9
            ]);

            $stockStmt->execute([
                'id'   => $item['product']['id'],
                'qty'  => $item['qty'],
                'qty2' => $item['qty'],
            ]);

            if ($stockStmt->rowCount() === 0) {
                throw new RuntimeException('Պահեստում բավարար չէ՝ ' . $item['product']['name']);
            }
        }

        $pdo->commit();

        return $orderId;

    } catch (Throwable $e) {
        $pdo->rollBack();          // ⭐ ամեն ինչ հետ է գլորվում
        throw $e;
    }
}

function getOrder(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM orders WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $order = $stmt->fetch();

    if (!$order) {
        return null;
    }

    // Երեք աղյուսակ՝ պատվերի տողերը ապրանքների անուններով — Դաս 9
    $stmt = db()->prepare('
        SELECT oi.quantity, oi.price, oi.quantity * oi.price AS line_total,
               p.name, p.slug, p.image
        FROM order_items oi
        INNER JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = :id
        ORDER BY oi.id
    ');
    $stmt->execute(['id' => $id]);

    $order['items'] = array_map('normalizeOrderRow', $stmt->fetchAll());
    $order['total'] = (int) $order['total'];
    $order['shipping'] = (int) $order['shipping'];

    return $order;
}

function normalizeOrderRow(array $row): array
{
    foreach (['quantity', 'price', 'line_total'] as $key) {
        $row[$key] = (int) $row[$key];
    }

    return $row;
}

/** Ադմինի ցուցակ՝ տողերի քանակով և ստուգման գումարով — Դաս 9-ի տնային */
function getOrders(): array
{
    return db()->query('
        SELECT o.id, o.customer_name, o.phone, o.total, o.status, o.created_at,
               COUNT(oi.id)                 AS line_count,
               SUM(oi.quantity)             AS units,
               SUM(oi.quantity * oi.price)  AS items_total
        FROM orders o
        LEFT JOIN order_items oi ON oi.order_id = o.id
        GROUP BY o.id
        ORDER BY o.created_at DESC, o.id DESC
    ')->fetchAll();
}

/** Թվերը, որ ցույց ենք տալիս ադմինի գլխավոր էջում — GROUP BY, Դաս 9 */
function getStats(): array
{
    return db()->query('
        SELECT c.name, COUNT(p.id) AS products, COALESCE(SUM(p.stock), 0) AS units
        FROM categories c
        LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
        GROUP BY c.id, c.name
        ORDER BY c.id
    ')->fetchAll();
}
