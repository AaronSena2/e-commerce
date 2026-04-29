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
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3">
            <?php foreach ($products as $i => $product): ?>
                <?php $inStock = (int) $product['stock'] > 0; ?>
                <div class="col fade-up" style="animation-delay:<?= min($i * 0.05, 0.4) ?>s">
                    <a href="product.php?id=<?= (int) $product['id'] ?>" class="text-decoration-none">
                        <div class="card product-card h-100<?= $inStock ? '' : ' out-of-stock' ?>">
                            <div class="card-img-wrapper">
                                <div class="card-img-placeholder">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <span class="price">$<?= number_format((float) $product['price'], 2) ?></span>
                                <h5 class="card-title">
                                    <?= htmlspecialchars($product['name']) ?>
                                </h5>
                                <p class="card-meta mb-0">
                                    <?= $inStock ? 'Available' : 'Sold Out' ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
