<?php
/**
 * order_success.php — Order confirmation page
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';
require_once __DIR__ . '/../app/models/Order.php';

// Validate order_id
$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);

if (!$order_id || $order_id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $order = order_get_by_id($order_id);
    $items = $order ? order_get_items($order_id) : [];
} catch (RuntimeException $e) {
    $order = null;
    $items = [];
}

if ($order === null) {
    header('Location: index.php');
    exit;
}

$page_title = 'Order Confirmed #' . $order_id . ' — ShopMVP';
require_once __DIR__ . '/../app/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Success banner -->
            <div class="text-center py-5 fade-up">
                <div class="success-icon-wrap">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h1 class="h2 fw-bold mb-2" style="letter-spacing:-.02em;">Thank you for your order!</h1>
                <p class="text-muted fs-5 mb-1">
                    Your order <strong class="gradient-text">#<?= (int) $order['id'] ?></strong> has been placed successfully.
                </p>
                <p class="text-muted">A confirmation will be sent to <strong><?= htmlspecialchars($order['email']) ?></strong>.</p>
            </div>

            <!-- Order details card -->
            <div class="card mb-4 fade-up fade-up-1">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>
                        <i class="bi bi-bag-check me-2"></i>
                        Order #<?= (int) $order['id'] ?>
                    </span>
                    <small class="text-muted fw-normal">
                        <?= htmlspecialchars(date('F j, Y', strtotime($order['created_at']))) ?>
                    </small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="ps-4"><?= htmlspecialchars($item['name']) ?></td>
                                        <td class="text-center"><?= (int) $item['quantity'] ?></td>
                                        <td class="text-end text-muted">$<?= number_format((float) $item['price'], 2) ?></td>
                                        <td class="text-end fw-semibold pe-4" style="color:var(--brand-1);">
                                            $<?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr style="background:var(--brand-grad-soft);">
                                    <td colspan="3" class="text-end fw-bold ps-4">Order Total</td>
                                    <td class="text-end pe-4 fw-bold fs-5">
                                        <span class="gradient-text">$<?= number_format((float) $order['total'], 2) ?></span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-body pt-0 pb-4 px-4">
                    <hr style="border-color:var(--border);">
                    <h6 class="text-muted text-uppercase small mb-2" style="letter-spacing:.08em;">
                        <i class="bi bi-geo-alt me-1"></i>Shipping To
                    </h6>
                    <address class="mb-0" style="font-size:.9rem;line-height:1.8;">
                        <strong><?= htmlspecialchars($order['name']) ?></strong><br>
                        <?= htmlspecialchars($order['address']) ?><br>
                        <?= htmlspecialchars($order['city']) ?>,
                        <?= htmlspecialchars($order['state']) ?>
                        <?= htmlspecialchars($order['zip']) ?><br>
                        <?php if (!empty($order['phone'])): ?>
                            <i class="bi bi-telephone me-1 text-muted"></i><?= htmlspecialchars($order['phone']) ?>
                        <?php endif; ?>
                    </address>
                </div>
            </div>

            <div class="text-center pb-4 fade-up fade-up-2">
                <a href="index.php" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-shop me-2"></i>Continue Shopping
                </a>
            </div>

        </div>
    </div>
</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
