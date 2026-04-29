<?php
/**
 * Product model — query functions using PDO prepared statements.
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Returns all active products ordered by name.
 */
function product_get_all(): array
{
    $pdo  = get_db();
    $stmt = $pdo->query('SELECT * FROM products ORDER BY name ASC');
    return $stmt->fetchAll();
}

/**
 * Returns a single product by ID, or null if not found.
 */
function product_get_by_id(int $id): ?array
{
    $pdo  = get_db();
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $row  = $stmt->fetch();
    return $row ?: null;
}
