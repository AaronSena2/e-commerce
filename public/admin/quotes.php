<?php
/**
 * admin/quotes.php — List all quote requests with status filter and inline status update.
 */

// Admin pages live two levels deep; set base_url before header.php runs.
$base_url = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\') . '/..';
$base_url = preg_replace('#/+#', '/', $base_url);

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/lib/session.php';
require_once __DIR__ . '/../../app/models/QuoteRequest.php';

$allowed_statuses = ['new', 'processing', 'quoted', 'closed'];

// ------------------------------------------------------------------
// Handle inline status update (POST)
// ------------------------------------------------------------------
$update_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $update_id     = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
    $update_status = $_POST['new_status'] ?? '';

    if ($update_id && $update_id > 0 && in_array($update_status, $allowed_statuses, true)) {
        try {
            quote_request_update_status($update_id, $update_status);
        } catch (Exception $e) {
            $update_error = 'Could not update status: ' . htmlspecialchars($e->getMessage());
        }
    }

    // Redirect to preserve filter + avoid re-submit on refresh
    $redirect = 'quotes.php';
    if (!empty($_POST['status_filter'])) {
        $redirect .= '?status=' . urlencode($_POST['status_filter']);
    }
    if (!$update_error) {
        header('Location: ' . $redirect);
        exit;
    }
}

// ------------------------------------------------------------------
// Apply status filter from query string
// ------------------------------------------------------------------
$filter_status = $_GET['status'] ?? '';
if (!in_array($filter_status, $allowed_statuses, true)) {
    $filter_status = '';
}

$quotes = quote_request_get_all($filter_status !== '' ? $filter_status : null);

// ------------------------------------------------------------------
// Status badge helper
// ------------------------------------------------------------------
$status_class = [
    'new'        => 'bg-primary',
    'processing' => 'bg-warning text-dark',
    'quoted'     => 'bg-info text-dark',
    'closed'     => 'bg-secondary',
];

$page_title = 'Quote Requests — Admin — ShopMVP';
require_once __DIR__ . '/../../app/includes/header.php';
?>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4 fade-up">
        <h1 class="admin-page-title mb-0">
            <i class="bi bi-chat-quote me-2"></i>Quote Requests
        </h1>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-speedometer2 me-1"></i>Dashboard
        </a>
    </div>

    <?php if ($update_error): ?>
        <div class="alert alert-danger fade-up"><?= $update_error ?></div>
    <?php endif; ?>

    <!-- Status filter tabs -->
    <ul class="nav nav-tabs mb-4 fade-up fade-up-1">
        <li class="nav-item">
            <a class="nav-link <?= $filter_status === '' ? 'active' : '' ?>" href="quotes.php">
                All
            </a>
        </li>
        <?php foreach ($allowed_statuses as $s): ?>
            <li class="nav-item">
                <a class="nav-link <?= $filter_status === $s ? 'active' : '' ?>"
                   href="quotes.php?status=<?= htmlspecialchars($s) ?>">
                    <?= htmlspecialchars(ucfirst($s)) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if (empty($quotes)): ?>
        <div class="alert alert-secondary fade-up fade-up-1">
            No quote requests found<?= $filter_status !== '' ? ' for status <strong>' . htmlspecialchars(ucfirst($filter_status)) . '</strong>' : '' ?>.
        </div>
    <?php else: ?>
        <div class="card fade-up fade-up-1">
            <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Message</th>
                        <th class="pe-4">Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $qr): ?>
                        <tr>
                            <td class="ps-4 fw-semibold"><?= (int) $qr['id'] ?></td>
                            <td class="text-nowrap text-muted small">
                                <?= htmlspecialchars(date('Y-m-d', strtotime($qr['created_at']))) ?>
                            </td>
                            <td>
                                <?php if (!empty($qr['product_name'])): ?>
                                    <a href="<?= $base_url ?>/public/product.php?id=<?= (int) $qr['product_id'] ?>"
                                       class="text-decoration-none fw-semibold" style="color:var(--brand-1);">
                                        <?= htmlspecialchars($qr['product_name']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $qr['name'] !== null ? htmlspecialchars($qr['name']) : '<span class="text-muted">—</span>' ?></td>
                            <td><?= htmlspecialchars($qr['email']) ?></td>
                            <td><?= $qr['phone'] !== null ? htmlspecialchars($qr['phone']) : '<span class="text-muted">—</span>' ?></td>
                            <td><?= (int) $qr['quantity'] ?></td>
                            <td>
                                <span class="badge <?= $status_class[$qr['status']] ?? 'bg-secondary' ?>">
                                    <?= htmlspecialchars(ucfirst($qr['status'])) ?>
                                </span>
                            </td>
                            <td class="small text-muted" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?php if (!empty($qr['message'])): ?>
                                    <span title="<?= htmlspecialchars($qr['message']) ?>">
                                        <?= htmlspecialchars(mb_strimwidth($qr['message'], 0, 60, '…')) ?>
                                    </span>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="pe-4">
                                <form method="post" action="quotes.php" class="d-flex gap-1 align-items-center">
                                    <input type="hidden" name="request_id"    value="<?= (int) $qr['id'] ?>">
                                    <input type="hidden" name="status_filter" value="<?= htmlspecialchars($filter_status) ?>">
                                    <select name="new_status" class="form-select form-select-sm" style="width:auto;">
                                        <?php foreach ($allowed_statuses as $s): ?>
                                            <option value="<?= $s ?>"
                                                <?= $s === $qr['status'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars(ucfirst($s)) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="update_status"
                                            class="btn btn-sm btn-outline-primary text-nowrap">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div><!-- /table-responsive -->
            </div><!-- /card-body -->
        </div><!-- /card -->
        <p class="text-muted small mt-2"><?= count($quotes) ?> request(s) shown.</p>
    <?php endif; ?>

</div><!-- /container -->

<?php require_once __DIR__ . '/../../app/includes/footer.php'; ?>
