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

    <!-- Hero Banner -->
    <div class="shop-hero fade-up">
        <div class="hero-badge">
            <i class="bi bi-stars"></i> New arrivals just dropped
        </div>
        <h1>Discover Amazing Products</h1>
        <p>Browse our curated collection of top-quality tech accessories, all at great prices.</p>
        <a href="#products" class="btn btn-light fw-bold px-4 rounded-pill shadow-sm" style="color:#6366f1;">
            <i class="bi bi-arrow-down me-1"></i> Shop Now
        </a>
    </div>

    <!-- Section heading -->
    <div class="d-flex align-items-center justify-content-between mb-4 fade-up fade-up-1" id="products">
        <h2 class="section-heading mb-0">
            <i class="bi bi-grid-3x3-gap"></i>All Products
        </h2>
        <?php if (!empty($products)): ?>
            <span class="badge rounded-pill" style="background:var(--brand-grad-soft);color:var(--brand-1);border:1px solid var(--border);padding:.45em .9em;font-size:.8rem;font-weight:700;">
                <?= count($products) ?> item<?= count($products) !== 1 ? 's' : '' ?>
            </span>
        <?php endif; ?>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger fade-up fade-up-1">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Could not load products. Please check your database connection.<br>
            <small class="text-muted"><?= htmlspecialchars($error) ?></small>
        </div>
    <?php elseif (empty($products)): ?>
        <div class="alert alert-info fade-up fade-up-1">
            <i class="bi bi-info-circle me-2"></i>No products found. Import <code>sql/schema.sql</code> to add seed data.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
            <?php foreach ($products as $i => $product): ?>
                <div class="col fade-up" style="animation-delay:<?= min($i * 0.05, 0.4) ?>s">
                    <div class="card product-card h-100">
                        <a href="product.php?id=<?= (int) $product['id'] ?>" class="text-decoration-none text-dark">
                            <div class="card-img-placeholder">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1" title="<?= htmlspecialchars($product['name']) ?>">
                                    <?= htmlspecialchars($product['name']) ?>
                                </h5>
                                <p class="card-text text-muted small flex-grow-1 mt-1" style="
                                    overflow: hidden;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;">
                                    <?= htmlspecialchars($product['description'] ?? '') ?>
                                </p>
                                <div class="d-flex align-items-center justify-content-between mt-3">
                                    <span class="price">$<?= number_format((float) $product['price'], 2) ?></span>
                                    <?php if ((int) $product['stock'] > 0): ?>
                                        <span class="badge" style="background:rgba(16,185,129,.12);color:#059669;border:1px solid rgba(16,185,129,.25);">In Stock</span>
                                    <?php else: ?>
                                        <span class="badge" style="background:rgba(107,114,128,.1);color:#6b7280;border:1px solid rgba(107,114,128,.2);">Out of Stock</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                        <div class="card-footer">
                            <?php if ((int) $product['stock'] > 0): ?>
                                <a href="product.php?id=<?= (int) $product['id'] ?>"
                                   class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-eye me-1"></i>View Details
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm w-100" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
