<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\CartItem;
use App\Models\PhysicalProduct;
use App\Services\CartSummary;
use PDO;
use RuntimeException;
use Throwable;

class OrderRepository
{
    public const STATUS = [
        'new'       => 'Նոր',
        'paid'      => 'Վճարված',
        'shipped'   => 'Ճանապարհին',
        'cancelled' => 'Չեղարկված',
    ];

    public function __construct(private PDO $pdo) {}

    /**
     * Պատվեր տրանզակցիայով — PHP Դաս 11, 14-ի տնային
     * @param CartItem[] $items
     */
    public function create(array $customer, array $items, CartSummary $summary, ?int $userId): int
    {
        $this->pdo->beginTransaction();

        try {
            $isPickup = $customer['delivery'] === 'pickup';

            $stmt = $this->pdo->prepare('
                INSERT INTO orders
                    (user_id, customer_name, email, phone, delivery, region, address, payment, comment, shipping, total)
                VALUES
                    (:user_id, :name, :email, :phone, :delivery, :region, :address, :payment, :comment, :shipping, :total)
            ');
            $stmt->execute([
                'user_id'  => $userId,
                'name'     => trim($customer['name']),
                'email'    => trim($customer['email'] ?? '') ?: null,
                'phone'    => trim($customer['phone']),
                'delivery' => $customer['delivery'],
                'region'   => $isPickup ? null : $customer['region'],
                'address'  => $isPickup ? null : trim($customer['address']),
                'payment'  => $customer['payment'],
                'comment'  => trim($customer['comment'] ?? '') ?: null,
                'shipping' => $summary->shipping,
                'total'    => $summary->total,
            ]);

            $orderId = (int) $this->pdo->lastInsertId();

            $itemStmt = $this->pdo->prepare('
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (:order_id, :product_id, :quantity, :price)
            ');

            $stockStmt = $this->pdo->prepare('
                UPDATE products SET stock = stock - :qty
                WHERE id = :id AND is_active = 1 AND stock >= :qty2
            ');

            foreach ($items as $item) {
                $itemStmt->execute([
                    'order_id'   => $orderId,
                    'product_id' => $item->product->id,
                    'quantity'   => $item->qty,
                    'price'      => $item->product->finalPrice(),
                ]);

                // Թվային ապրանքը պահեստ չունի — պոլիմորֆիզմ, Դաս 13
                if (!$item->product instanceof PhysicalProduct) {
                    continue;
                }

                $stockStmt->execute(['id' => $item->product->id, 'qty' => $item->qty, 'qty2' => $item->qty]);

                if ($stockStmt->rowCount() === 0) {
                    throw new RuntimeException('Պահեստում բավարար չէ՝ ' . $item->product->name);
                }
            }

            $this->pdo->commit();

            return $orderId;

        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();

        if (!$order) {
            return null;
        }

        $stmt = $this->pdo->prepare('
            SELECT oi.quantity, oi.price, oi.quantity * oi.price AS line_total, p.name, p.slug
            FROM order_items oi
            INNER JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :id
            ORDER BY oi.id
        ');
        $stmt->execute(['id' => $id]);
        $order['items'] = $stmt->fetchAll();

        return $order;
    }

    /** Մուտք գործած գնորդի պատվերները */
    public function forUser(int $userId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT o.id, o.total, o.status, o.created_at, SUM(oi.quantity) AS units
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            WHERE o.user_id = :user
            GROUP BY o.id
            ORDER BY o.created_at DESC, o.id DESC
        ');
        $stmt->execute(['user' => $userId]);

        return $stmt->fetchAll();
    }

    public function all(): array
    {
        return $this->pdo->query('
            SELECT o.id, o.customer_name, o.phone, o.total, o.status, o.created_at,
                   COUNT(oi.id) AS line_count, SUM(oi.quantity) AS units
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            GROUP BY o.id
            ORDER BY o.created_at DESC, o.id DESC
        ')->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool
    {
        if (!array_key_exists($status, self::STATUS)) {
            return false;
        }

        $stmt = $this->pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function stats(): array
    {
        return $this->pdo->query('
            SELECT c.name, COUNT(p.id) AS products, COALESCE(SUM(p.stock), 0) AS units
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
            GROUP BY c.id, c.name
            ORDER BY c.id
        ')->fetchAll();
    }
}
