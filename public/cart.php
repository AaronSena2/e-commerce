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

    <h1 class="h3 mb-4">
        <i class="bi bi-cart3 me-2 text-primary"></i>Your Cart
    </h1>

    <?php if (empty($cart)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size:4rem; color:#adb5bd;"></i>
            <p class="mt-3 fs-5 text-muted">Your cart is empty.</p>
            <a href="index.php" class="btn btn-primary mt-2">
                <i class="bi bi-arrow-left me-1"></i>Continue Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Cart items -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
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
                                            <!-- Product name/image -->
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="cart-img d-flex align-items-center justify-content-center bg-light rounded">
                                                        <i class="bi bi-box-seam text-muted fs-4"></i>
                                                    </div>
                                                    <a href="product.php?id=<?= (int) $item['product_id'] ?>"
                                                       class="text-decoration-none text-dark fw-semibold">
                                                        <?= htmlspecialchars($item['name']) ?>
                                                    </a>
                                                </div>
                                            </td>

                                            <!-- Unit price -->
                                            <td class="text-center">
                                                $<?= number_format((float) $item['price'], 2) ?>
                                            </td>

                                            <!-- Quantity form -->
                                            <td class="text-center">
                                                <form method="post" action="cart.php" class="d-inline-flex align-items-center gap-1">
                                                    <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                                    <input
                                                        type="number"
                                                        id="qty-<?= (int) $item['product_id'] ?>"
                                                        name="quantity"
                                                        value="<?= (int) $item['quantity'] ?>"
                                                        min="1"
                                                        max="999"
                                                        class="form-control form-control-sm qty-input text-center"
                                                        style="width:65px;"
                                                    >
                                                    <button type="submit" name="update_qty" class="btn btn-sm btn-outline-secondary" title="Update">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                            </td>

                                            <!-- Subtotal -->
                                            <td class="text-end fw-semibold">
                                                $<?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?>
                                            </td>

                                            <!-- Remove -->
                                            <td class="pe-4">
                                                <form method="post" action="cart.php">
                                                    <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                                    <button
                                                        type="submit"
                                                        name="remove_item"
                                                        class="btn btn-sm btn-outline-danger btn-remove-item"
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
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <?php foreach ($cart as $item): ?>
                                <dt class="col-8 fw-normal text-muted small text-truncate">
                                    <?= htmlspecialchars($item['name']) ?>
                                    <span class="text-dark">× <?= (int) $item['quantity'] ?></span>
                                </dt>
                                <dd class="col-4 text-end small mb-1">
                                    $<?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?>
                                </dd>
                            <?php endforeach; ?>
                        </dl>
                        <hr>
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <span>Total</span>
                            <span class="text-primary">$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-4">
                        <a href="checkout.php" class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
