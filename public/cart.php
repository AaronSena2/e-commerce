<?php
/**
 * cart.php — View cart, update quantities, remove items
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';

// ------------------------------------------------------------------
// Handle actions (POST)
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Update quantity
    if (isset($_POST['update_qty'])) {
        $pid = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $qty = filter_input(INPUT_POST, 'quantity',   FILTER_VALIDATE_INT);

        if ($pid && $pid > 0) {
            cart_update($pid, (int) $qty);
        }
        header('Location: cart.php');
        exit;
    }

    // Remove item
    if (isset($_POST['remove_item'])) {
        $pid = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        if ($pid && $pid > 0) {
            cart_remove($pid);
        }
        header('Location: cart.php');
        exit;
    }
}

$cart  = cart_get();
$total = cart_total();

$page_title = 'Your Cart — ShopMVP';
require_once __DIR__ . '/../app/includes/header.php';
?>

<div class="container">

    <h1 class="section-heading mb-4 fade-up">
        <i class="bi bi-cart3"></i>Your Cart
    </h1>

    <?php if (empty($cart)): ?>
        <div class="text-center py-5 fade-up fade-up-1">
            <div style="width:90px;height:90px;border-radius:50%;background:var(--brand-grad-soft);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:2.5rem;color:var(--brand-1);">
                <i class="bi bi-cart-x"></i>
            </div>
            <p class="fs-5 fw-semibold mb-1" style="color:var(--text-main);">Your cart is empty</p>
            <p class="text-muted mb-4">Looks like you haven&rsquo;t added anything yet.</p>
            <a href="index.php" class="btn btn-primary px-4">
                <i class="bi bi-arrow-left me-1"></i>Continue Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Cart items -->
            <div class="col-lg-8 fade-up fade-up-1">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center" style="width:150px;">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $item): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="cart-img">
                                                        <i class="bi bi-box-seam"></i>
                                                    </div>
                                                    <a href="product.php?id=<?= (int) $item['product_id'] ?>"
                                                       class="text-decoration-none fw-semibold" style="color:var(--text-main);">
                                                        <?= htmlspecialchars($item['name']) ?>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-center fw-semibold" style="color:var(--text-muted);">
                                                $<?= number_format((float) $item['price'], 2) ?>
                                            </td>
                                            <td class="text-center">
                                                <form method="post" action="cart.php" class="d-inline-flex align-items-center gap-1">
                                                    <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        value="<?= (int) $item['quantity'] ?>"
                                                        min="1" max="999"
                                                        class="form-control form-control-sm qty-input text-center"
                                                        style="width:65px;"
                                                    >
                                                    <button type="submit" name="update_qty"
                                                            class="btn btn-sm btn-outline-secondary" title="Update">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end fw-bold" style="color:var(--brand-1);">
                                                $<?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?>
                                            </td>
                                            <td class="pe-4">
                                                <form method="post" action="cart.php">
                                                    <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                                    <button type="submit" name="remove_item"
                                                            class="btn btn-sm btn-remove-item"
                                                            style="color:#ef4444;border:none;background:rgba(239,68,68,.08);border-radius:.4rem;padding:.35rem .5rem;"
                                                            title="Remove">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order summary -->
            <div class="col-lg-4 fade-up fade-up-2">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-receipt me-2"></i>Order Summary
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <?php foreach ($cart as $item): ?>
                                <dt class="col-8 fw-normal small text-truncate" style="color:var(--text-muted);">
                                    <?= htmlspecialchars($item['name']) ?>
                                    <span style="color:var(--text-main);">× <?= (int) $item['quantity'] ?></span>
                                </dt>
                                <dd class="col-4 text-end small mb-1">
                                    $<?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?>
                                </dd>
                            <?php endforeach; ?>
                        </dl>
                        <hr style="border-color:var(--border);">
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total</span>
                            <span class="gradient-text">$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top" style="border-color:var(--border)!important;padding:1rem 1.25rem 1.25rem;">
                        <a href="checkout.php" class="btn btn-success w-100 btn-lg fw-semibold">
                            <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
