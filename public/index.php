<?php
/**
 * index.php — Product listing (Facebook Marketplace layout)
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';
require_once __DIR__ . '/../app/models/Product.php';

$page_title   = 'Marketplace — ShopMVP';
$main_class   = 'p-0';         // override default py-4 so the MP layout fills edge-to-edge

try {
    $products = product_get_all();
} catch (RuntimeException $e) {
    $error    = $e->getMessage();
    $products = [];
}

require_once __DIR__ . '/../app/includes/header.php';

/* Category list — shared by sidebar and mobile pill bar */
$categories = [
    ['icon' => 'bi-grid',              'label' => 'All Categories',    'active' => true],
    ['icon' => 'bi-phone',             'label' => 'Electronics'],
    ['icon' => 'bi-car-front',         'label' => 'Vehicles'],
    ['icon' => 'bi-house-door',        'label' => 'Property & Rentals'],
    ['icon' => 'bi-bag',               'label' => 'Apparel'],
    ['icon' => 'bi-tree',              'label' => 'Garden & Outdoor'],
    ['icon' => 'bi-controller',        'label' => 'Toys & Games'],
    ['icon' => 'bi-lamp',              'label' => 'Home & Living'],
    ['icon' => 'bi-music-note-beamed', 'label' => 'Musical Instruments'],
    ['icon' => 'bi-bicycle',           'label' => 'Sports & Outdoors'],
    ['icon' => 'bi-heart',             'label' => 'Pet Supplies'],
    ['icon' => 'bi-book',              'label' => 'Books & Media'],
];
?>

<!-- Mobile horizontal category bar — visible only below lg -->
<div class="mp-cat-bar d-flex d-lg-none">
    <?php foreach ($categories as $cat): ?>
        <a href="#" class="mp-cat-pill<?= !empty($cat['active']) ? ' active' : '' ?>">
            <i class="bi <?= $cat['icon'] ?>"></i>
            <?= htmlspecialchars($cat['label']) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- ── Marketplace two-pane layout ── -->
<div class="mp-wrapper">

    <!-- Sidebar — d-none d-lg-block via Bootstrap utilities -->
    <aside class="mp-sidebar d-none d-lg-block">
        <div class="mp-sidebar-title">
            <i class="bi bi-shop-window"></i> Marketplace
        </div>

        <div class="mp-sidebar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search Marketplace">
        </div>

        <a href="<?= $base_url ?>/public/quote_request.php" class="mp-create-btn">
            <i class="bi bi-plus-lg"></i> Create new listing
        </a>

        <hr class="mp-divider">

        <p class="mp-section-label">Browse by category</p>
        <?php foreach ($categories as $cat): ?>
            <a href="#" class="mp-cat-link<?= !empty($cat['active']) ? ' active' : '' ?>">
                <span class="mp-cat-icon"><i class="bi <?= $cat['icon'] ?>"></i></span>
                <?= htmlspecialchars($cat['label']) ?>
            </a>
        <?php endforeach; ?>

        <hr class="mp-divider">

        <p class="mp-sidebar-footer">
            <a href="#">Privacy</a> ·
            <a href="#">Terms</a> ·
            <a href="#">Cookies</a><br>
            &copy; <?= date('Y') ?> ShopMVP
        </p>
    </aside>

    <!-- ── Main content ── -->
    <div class="mp-main">
        <div class="mp-content">

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Could not load products. Please check your database connection.<br>
                    <small class="text-muted"><?= htmlspecialchars($error) ?></small>
                </div>
            <?php else: ?>

                <h2 class="mp-content-title">Today's picks</h2>

                <?php if (empty($products)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>No products found.
                        Import <code>sql/schema.sql</code> to add seed data.
                    </div>
                <?php else: ?>
                    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3">
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
                                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                            <span class="card-stock-badge <?= $inStock ? 'in-stock' : 'sold-out' ?>">
                                                <?= $inStock ? 'Available' : 'Sold Out' ?>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </div><!-- /mp-content -->
    </div><!-- /mp-main -->

</div><!-- /mp-wrapper -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
