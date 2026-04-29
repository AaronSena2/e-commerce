<?php
/**
 * QuoteRequest model — CRUD functions using PDO prepared statements.
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Inserts a new quote request and returns the new record ID.
 *
 * @param array $data  Keys: product_id, email, name (opt), phone (opt), quantity (opt), message (opt)
 * @return int  The new quote_request ID
 */
function quote_request_create(array $data): int
{
    $pdo  = get_db();
    $stmt = $pdo->prepare(
        'INSERT INTO quote_requests (product_id, name, email, phone, quantity, message)
         VALUES (:product_id, :name, :email, :phone, :quantity, :message)'
    );
    $stmt->execute([
        ':product_id' => (int) $data['product_id'],
        ':name'       => $data['name']     ?? null,
        ':email'      => $data['email'],
        ':phone'      => $data['phone']    ?? null,
        ':quantity'   => isset($data['quantity']) ? (int) $data['quantity'] : 1,
        ':message'    => $data['message']  ?? null,
    ]);
    return (int) $pdo->lastInsertId();
}

/**
 * Returns all quote requests, optionally filtered by status, ordered newest first.
 * Each row includes the associated product name.
 *
 * @param string|null $status  'new'|'processing'|'quoted'|'closed' or null for all
 * @return array
 */
function quote_request_get_all(?string $status = null): array
{
    $pdo = get_db();

    if ($status !== null) {
        $stmt = $pdo->prepare(
            'SELECT qr.*, p.name AS product_name
             FROM quote_requests qr
             LEFT JOIN products p ON p.id = qr.product_id
             WHERE qr.status = :status
             ORDER BY qr.created_at DESC'
        );
        $stmt->execute([':status' => $status]);
    } else {
        $stmt = $pdo->query(
            'SELECT qr.*, p.name AS product_name
             FROM quote_requests qr
             LEFT JOIN products p ON p.id = qr.product_id
             ORDER BY qr.created_at DESC'
        );
    }

    return $stmt->fetchAll();
}

/**
 * Returns a single quote request by ID (with product name), or null if not found.
 */
function quote_request_get_by_id(int $id): ?array
{
    $pdo  = get_db();
    $stmt = $pdo->prepare(
        'SELECT qr.*, p.name AS product_name
         FROM quote_requests qr
         LEFT JOIN products p ON p.id = qr.product_id
         WHERE qr.id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Updates the status of a quote request.
 *
 * @throws InvalidArgumentException for unrecognised status values
 */
function quote_request_update_status(int $id, string $status): void
{
    $allowed = ['new', 'processing', 'quoted', 'closed'];
    if (!in_array($status, $allowed, true)) {
        throw new InvalidArgumentException('Invalid status value.');
    }
    $pdo  = get_db();
    $stmt = $pdo->prepare('UPDATE quote_requests SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $id]);
}

/**
 * Returns counts of quote requests grouped by status.
 * Returns an associative array: ['new' => N, 'processing' => N, ...] with 0 defaults.
 */
function quote_request_counts_by_status(): array
{
    $pdo    = get_db();
    $stmt   = $pdo->query('SELECT status, COUNT(*) AS cnt FROM quote_requests GROUP BY status');
    $rows   = $stmt->fetchAll();
    $counts = ['new' => 0, 'processing' => 0, 'quoted' => 0, 'closed' => 0];
    foreach ($rows as $row) {
        $counts[$row['status']] = (int) $row['cnt'];
    }
    return $counts;
}

/**
 * Returns the N most recent quote requests with product name.
 */
function quote_request_get_recent(int $limit = 5): array
{
    $pdo  = get_db();
    $stmt = $pdo->prepare(
        'SELECT qr.*, p.name AS product_name
         FROM quote_requests qr
         LEFT JOIN products p ON p.id = qr.product_id
         ORDER BY qr.created_at DESC
         LIMIT :limit'
    );
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
