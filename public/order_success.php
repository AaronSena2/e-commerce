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
            <div class="text-center py-5">
                <div class="order-success-icon mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h1 class="h2 mb-2">Thank you for your order!</h1>
                <p class="text-muted fs-5 mb-1">
                    Your order <strong>#<?= (int) $order['id'] ?></strong> has been placed successfully.
                </p>
                <p class="text-muted">A confirmation will be sent to <strong><?= htmlspecialchars($order['email']) ?></strong>.</p>
            </div>

            <!-- Order details card -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0">
                        <i class="bi bi-bag-check me-2 text-success"></i>
                        Order #<?= (int) $order['id'] ?>
                        <small class="text-muted fw-normal ms-2" style="font-size:0.8em;">
                            <?= htmlspecialchars(date('F j, Y', strtotime($order['created_at']))) ?>
                        </small>
                    </h5>
                </div>
                <div class="card-body">

                    <!-- Items table -->
                    <div class="table-responsive mb-4">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td class="text-center"><?= (int) $item['quantity'] ?></td>
                                        <td class="text-end">$<?= number_format((float) $item['price'], 2) ?></td>
                                        <td class="text-end fw-semibold">
                                            $<?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="3" class="text-end">Order Total</td>
                                    <td class="text-end text-primary fs-5">
                                        $<?= number_format((float) $order['total'], 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Shipping address -->
                    <div class="row">
                        <div class="col-sm-6">
                            <h6 class="text-muted text-uppercase small mb-2">
                                <i class="bi bi-geo-alt me-1"></i>Shipping To
                            </h6>
                            <address class="mb-0">
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

                </div>
            </div>

            <!-- Actions -->
            <div class="text-center pb-4">
                <a href="index.php" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-shop me-2"></i>Continue Shopping
                </a>
            </div>

        </div>
    </div>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
