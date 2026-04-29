<?php
/**
 * index.php — Product listing grid
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';
require_once __DIR__ . '/../app/models/Product.php';

$page_title = 'Products — ShopMVP';

try {
    $products = product_get_all();
} catch (RuntimeException $e) {
    $error = $e->getMessage();
    $products = [];
}

require_once __DIR__ . '/../app/includes/header.php';
?>

<div class="container">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>All Products
        </h1>
        <?php if (!empty($products)): ?>
            <span class="text-muted small"><?= count($products) ?> item<?= count($products) !== 1 ? 's' : '' ?></span>
        <?php endif; ?>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Could not load products. Please check your database connection.<br>
            <small class="text-muted"><?= htmlspecialchars($error) ?></small>
        </div>
    <?php elseif (empty($products)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>No products found. Import <code>sql/schema.sql</code> to add seed data.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card product-card h-100 shadow-sm">
                        <a href="product.php?id=<?= (int) $product['id'] ?>" class="text-decoration-none text-dark">
                            <div class="card-img-placeholder">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title" title="<?= htmlspecialchars($product['name']) ?>">
                                    <?= htmlspecialchars($product['name']) ?>
                                </h5>
                                <p class="card-text text-muted small flex-grow-1" style="
                                    overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;">
                                    <?= htmlspecialchars($product['description'] ?? '') ?>
                                </p>
                                <div class="d-flex align-items-center justify-content-between mt-3">
                                    <span class="price">$<?= number_format((float) $product['price'], 2) ?></span>
                                    <?php if ((int) $product['stock'] > 0): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">In Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Out of Stock</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <?php if ((int) $product['stock'] > 0): ?>
                                <a href="product.php?id=<?= (int) $product['id'] ?>"
                                   class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-eye me-1"></i>View Details
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm w-100" disabled>
                                    Out of Stock
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
