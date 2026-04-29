<?php
/**
 * admin/index.php — Admin dashboard: metrics, recent quote requests, recent orders.
 *
 * No authentication (consistent with no-login app). Structure allows auth to be
 * added later by wrapping the require_once block below in an auth check.
 */

// Admin pages live two levels deep under the project root (/public/admin/),
// so compute base_url before header.php does to keep links correct.
$base_url = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\') . '/..';
$base_url = preg_replace('#/+#', '/', $base_url);

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/lib/session.php';
require_once __DIR__ . '/../../app/models/Product.php';
require_once __DIR__ . '/../../app/models/Order.php';
require_once __DIR__ . '/../../app/models/QuoteRequest.php';

// ------------------------------------------------------------------
// Metrics
// ------------------------------------------------------------------
$pdo = get_db();

$total_products = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$total_orders   = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$total_quotes   = (int) $pdo->query('SELECT COUNT(*) FROM quote_requests')->fetchColumn();

$status_counts  = quote_request_counts_by_status();
$recent_quotes  = quote_request_get_recent(5);

$stmt_orders = $pdo->prepare(
    'SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit'
);
$stmt_orders->bindValue(':limit', 5, PDO::PARAM_INT);
$stmt_orders->execute();
$recent_orders = $stmt_orders->fetchAll();

// ------------------------------------------------------------------
// Status badge helper
// ------------------------------------------------------------------
$status_class = [
    'new'        => 'bg-primary',
    'processing' => 'bg-warning text-dark',
    'quoted'     => 'bg-info text-dark',
    'closed'     => 'bg-secondary',
];

$page_title = 'Admin Dashboard — ShopMVP';
require_once __DIR__ . '/../../app/includes/header.php';
?>

<div class="container">

    <!-- Page heading -->
    <div class="d-flex justify-content-between align-items-center mb-4 fade-up">
        <h1 class="admin-page-title mb-0">
            <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
        </h1>
        <a href="quotes.php" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-chat-quote me-1"></i>All Quote Requests
        </a>
    </div>

    <!-- Metric Cards -->
    <div class="row g-3 mb-4 fade-up fade-up-1">

        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-indigo h-100">
                <div class="card-body d-flex align-items-center gap-3 pb-2">
                    <i class="bi bi-box-seam stat-icon"></i>
                    <div>
                        <div class="stat-value"><?= $total_products ?></div>
                        <div class="stat-label">Products</div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= $base_url ?>/public/index.php">View store <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-emerald h-100">
                <div class="card-body d-flex align-items-center gap-3 pb-2">
                    <i class="bi bi-bag-check stat-icon"></i>
                    <div>
                        <div class="stat-value"><?= $total_orders ?></div>
                        <div class="stat-label">Orders</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-violet h-100">
                <div class="card-body d-flex align-items-center gap-3 pb-2">
                    <i class="bi bi-chat-quote stat-icon"></i>
                    <div>
                        <div class="stat-value"><?= $total_quotes ?></div>
                        <div class="stat-label">Quote Requests</div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="quotes.php">Manage <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card stat-amber h-100">
                <div class="card-body d-flex align-items-center gap-3 pb-2">
                    <i class="bi bi-bell stat-icon"></i>
                    <div>
                        <div class="stat-value"><?= $status_counts['new'] ?></div>
                        <div class="stat-label">New Requests</div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="quotes.php?status=new">Review <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

    </div>

    <!-- Lower section -->
    <div class="row g-3 mb-4 fade-up fade-up-2">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-pie-chart me-2"></i>Quote Requests by Status
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Status</th>
                                <th class="text-end pe-4">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($status_counts as $s => $cnt): ?>
                                <tr>
                                    <td class="ps-4">
                                        <a href="quotes.php?status=<?= htmlspecialchars($s) ?>"
                                           class="text-decoration-none">
                                            <span class="badge <?= $status_class[$s] ?? 'bg-secondary' ?>">
                                                <?= htmlspecialchars(ucfirst($s)) ?>
                                            </span>
                                        </a>
                                    </td>
                                    <td class="text-end pe-4 fw-bold"><?= $cnt ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-clock-history me-2"></i>Recent Orders (last 5)
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recent_orders)): ?>
                        <p class="text-muted p-3 mb-0">No orders yet.</p>
                    <?php else: ?>
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Customer</th>
                                    <th class="text-end">Total</th>
                                    <th class="pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold"><?= (int) $order['id'] ?></td>
                                        <td><?= htmlspecialchars($order['name']) ?></td>
                                        <td class="text-end fw-semibold" style="color:var(--brand-1);">
                                            $<?= number_format((float) $order['total'], 2) ?>
                                        </td>
                                        <td class="text-muted small pe-4">
                                            <?= htmlspecialchars(date('M j, Y', strtotime($order['created_at']))) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Quote Requests -->
    <div class="card mb-4 fade-up fade-up-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-chat-quote me-2"></i>Recent Quote Requests (last 5)</span>
            <a href="quotes.php" class="btn btn-outline-primary btn-sm">View All</a>
        </div>
        <div class="card-body p-0">
            <?php if (empty($recent_quotes)): ?>
                <p class="text-muted p-3 mb-0">No quote requests yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Product</th>
                                <th>Email</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th class="pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_quotes as $qr): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold"><?= (int) $qr['id'] ?></td>
                                    <td><?= htmlspecialchars($qr['product_name'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($qr['email']) ?></td>
                                    <td><?= (int) $qr['quantity'] ?></td>
                                    <td>
                                        <span class="badge <?= $status_class[$qr['status']] ?? 'bg-secondary' ?>">
                                            <?= htmlspecialchars(ucfirst($qr['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small pe-4">
                                        <?= htmlspecialchars(date('M j, Y', strtotime($qr['created_at']))) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div><!-- /container -->

<?php require_once __DIR__ . '/../../app/includes/footer.php'; ?>
