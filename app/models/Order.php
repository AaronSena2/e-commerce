<?php
/**
 * Order model — creation and retrieval using PDO prepared statements.
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Creates a new order and its items in a single transaction.
 *
 * @param array $customer  Keys: name, email, phone, address, city, state, zip
 * @param array $cart_items  Array of cart items from cart_get()
 * @param float $total
 * @return int  The new order ID
 * @throws RuntimeException on failure
 */
function order_create(array $customer, array $cart_items, float $total): int
{
    $pdo = get_db();

    $pdo->beginTransaction();

    try {
        // Insert the order header
        $stmt = $pdo->prepare(
            'INSERT INTO orders (name, email, phone, address, city, state, zip, total)
             VALUES (:name, :email, :phone, :address, :city, :state, :zip, :total)'
        );
        $stmt->execute([
            ':name'    => $customer['name'],
            ':email'   => $customer['email'],
            ':phone'   => $customer['phone'] ?? null,
            ':address' => $customer['address'],
            ':city'    => $customer['city'],
            ':state'   => $customer['state'],
            ':zip'     => $customer['zip'],
            ':total'   => $total,
        ]);

        $order_id = (int) $pdo->lastInsertId();

        // Insert each order item
        $item_stmt = $pdo->prepare(
            'INSERT INTO order_items (order_id, product_id, name, price, quantity)
             VALUES (:order_id, :product_id, :name, :price, :quantity)'
        );

        foreach ($cart_items as $item) {
            $item_stmt->execute([
                ':order_id'   => $order_id,
                ':product_id' => (int) $item['product_id'],
                ':name'       => $item['name'],
                ':price'      => (float) $item['price'],
                ':quantity'   => (int) $item['quantity'],
            ]);
        }

        $pdo->commit();
        return $order_id;

    } catch (Exception $e) {
        $pdo->rollBack();
        throw new RuntimeException('Order creation failed: ' . $e->getMessage());
    }
}

/**
 * Returns a single order by ID, or null if not found.
 */
function order_get_by_id(int $id): ?array
{
    $pdo  = get_db();
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $row  = $stmt->fetch();
    return $row ?: null;
}

/**
 * Returns all items belonging to an order.
 */
function order_get_items(int $order_id): array
{
    $pdo  = get_db();
    $stmt = $pdo->prepare(
        'SELECT oi.*, p.image
           FROM order_items oi
           JOIN products p ON p.id = oi.product_id
          WHERE oi.order_id = :order_id'
    );
    $stmt->execute([':order_id' => $order_id]);
    return $stmt->fetchAll();
}
