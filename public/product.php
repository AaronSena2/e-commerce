<?php
/**
 * product.php — Product detail page with "Add to Cart"
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';
require_once __DIR__ . '/../app/models/Product.php';

// ------------------------------------------------------------------
// Fetch product
// ------------------------------------------------------------------
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $product = product_get_by_id($id);
} catch (RuntimeException $e) {
    $product = null;
}

if ($product === null) {
    header('Location: index.php');
    exit;
}

// ------------------------------------------------------------------
// Handle "Add to Cart" POST
// ------------------------------------------------------------------
$success_msg = '';
$error_msg   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $qty = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if (!$qty || $qty < 1) {
        $error_msg = 'Please enter a valid quantity.';
    } elseif ($qty > (int) $product['stock']) {
        $error_msg = 'Not enough stock available.';
    } else {
        cart_add(
            (int) $product['id'],
            $product['name'],
            (float) $product['price'],
            $product['image'],
            $qty
        );
        $success_msg = htmlspecialchars($product['name']) . ' added to your cart.';
    }
}

$page_title = htmlspecialchars($product['name']) . ' — ShopMVP';
require_once __DIR__ . '/../app/includes/header.php';
?>

<div class="container">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 fade-up">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($product['name']) ?>
            </li>
        </ol>
    </nav>

    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show fade-up" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= $success_msg ?>
            <a href="cart.php" class="alert-link ms-2">View Cart</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show fade-up" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-5 fade-up fade-up-1">
        <!-- Product image -->
        <div class="col-md-5">
            <div class="product-detail-img shadow-sm">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>

        <!-- Product details -->
        <div class="col-md-7">
            <h1 class="h2 fw-800 mb-2" style="font-weight:800;letter-spacing:-.03em;">
                <?= htmlspecialchars($product['name']) ?>
            </h1>

            <div class="mb-4">
                <span class="price-tag">$<?= number_format((float) $product['price'], 2) ?></span>
            </div>

            <p style="color:var(--text-muted);line-height:1.75;margin-bottom:1.5rem;">
                <?= nl2br(htmlspecialchars($product['description'] ?? '')) ?>
            </p>

            <?php if ((int) $product['stock'] > 0): ?>
                <div class="mb-4">
                    <span class="badge fs-6 px-3 py-2 rounded-pill" style="background:rgba(16,185,129,.12);color:#059669;border:1px solid rgba(16,185,129,.3);">
                        <i class="bi bi-check-circle me-1"></i>In Stock
                        &mdash; <?= (int) $product['stock'] ?> available
                    </span>
                </div>

                <form method="post" action="product.php?id=<?= (int) $product['id'] ?>">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <label for="quantity" class="form-label mb-0">Qty:</label>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            class="form-control"
                            value="1"
                            min="1"
                            max="<?= (int) $product['stock'] ?>"
                            style="width:100px;"
                            required
                        >
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <a href="cart.php" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-cart3 me-1"></i>View Cart
                        </a>
                    </div>
                </form>

                <!-- Request a Quote -->
                <div class="mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <p class="text-muted small mb-2">Need a bulk deal or custom price?</p>
                    <a href="quote_request.php?product_id=<?= (int) $product['id'] ?>"
                       class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>Request a Quote
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-secondary mb-4">
                    <i class="bi bi-x-circle me-2"></i>This item is currently out of stock.
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Products
                    </a>
                    <a href="quote_request.php?product_id=<?= (int) $product['id'] ?>"
                       class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>Request a Quote
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
