<style>
    /* Sidebar Styling */
    .mp-sidebar {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-right: 1px solid #e9ecef;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        padding: 1rem;
        width: 220px;
        position: fixed;
        left: 0;
        height: 100vh;
        overflow-y: auto;
    }

    .mp-sidebar-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .mp-gear-btn {
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        font-size: 1.25rem;
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 6px;
    }

    .mp-gear-btn:hover {
        background-color: #e9ecef;
        color: #495057;
        transform: rotate(15deg);
    }

    .mp-sidebar-search {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .mp-sidebar-search i {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 0.9rem;
    }

    .mp-sidebar-search input {
        width: 100%;
        padding: 0.75rem 0.75rem 0.75rem 2.5rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .mp-sidebar-search input:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    .mp-nav {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .mp-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #495057;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .mp-nav-link:hover {
        background-color: #e7f1ff;
        color: #0d6efd;
        transform: translateX(4px);
    }

    .mp-nav-link.active {
        background-color: #0d6efd;
        color: #ffffff;
    }

    .mp-nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .mp-nav-label {
        flex: 1;
    }

    .mp-create-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        margin-bottom: 1.5rem;
    }

    .mp-create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
        color: #ffffff;
        text-decoration: none;
    }

    .mp-divider {
        border: none;
        border-top: 1px solid #e9ecef;
        margin: 1.5rem 0;
    }

    .mp-sidebar-block {
        margin-bottom: 1.5rem;
    }

    .mp-block-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    .mp-location-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #0d6efd;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .mp-location-link:hover {
        background-color: #e7f1ff;
        color: #0a58ca;
    }

    .mp-nav-compact {
        gap: 0.25rem;
    }

    .mp-nav-compact .mp-nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }

    .mp-sidebar-footer {
        font-size: 0.8rem;
        color: #6c757d;
        text-align: center;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
        line-height: 1.6;
    }

    .mp-sidebar-footer a {
        color: #0d6efd;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .mp-sidebar-footer a:hover {
        color: #0a58ca;
        text-decoration: underline;
    }
</style>

<?php
/**
 * index.php — Product listing (Facebook Marketplace layout)
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';
require_once __DIR__ . '/../app/models/Product.php';

$page_title   = 'Marketplace — ShopMVP';
$main_class   = 'p-0'; // edge-to-edge MP layout

try {
    $products = product_get_all();
} catch (RuntimeException $e) {
    $error    = $e->getMessage();
    $products = [];
}

require_once __DIR__ . '/../app/includes/header.php';

/* Category list — shared by sidebar and mobile pill bar */
$categories = [
    ['icon' => 'bi-grid',              'label' => 'Browse all',           'active' => true],
    ['icon' => 'bi-bell',              'label' => 'Notifications'],
    ['icon' => 'bi-inbox',             'label' => 'Inbox'],
    ['icon' => 'bi-shield-lock',       'label' => 'Marketplace access'],
    ['icon' => 'bi-bag',               'label' => 'Buying'],
    ['icon' => 'bi-tags',              'label' => 'Selling'],
];

$category_groups = [
    'Categories' => [
        ['icon' => 'bi-car-front', 'label' => 'Vehicles'],
        ['icon' => 'bi-house-door', 'label' => 'Property Rentals'],
        ['icon' => 'bi-phone', 'label' => 'Electronics'],
        ['icon' => 'bi-controller', 'label' => 'Toys & Games'],
    ],
];

$location_label = 'Kampala, Uganda · Within 65 km';
?>

<!-- Mobile horizontal bar (below lg) -->
<div class="mp-cat-bar d-flex d-lg-none">
    <a href="#" class="mp-cat-pill active"><i class="bi bi-grid"></i> Browse all</a>
    <a href="#" class="mp-cat-pill"><i class="bi bi-car-front"></i> Vehicles</a>
    <a href="#" class="mp-cat-pill"><i class="bi bi-house-door"></i> Property</a>
    <a href="#" class="mp-cat-pill"><i class="bi bi-phone"></i> Electronics</a>
</div>



<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (Facebook Marketplace style) -->
        <aside class="mp-sidebar d-none d-lg-block col-lg-3 col-xl-2 bg-white px-0" style="min-height: 100vh; border-right: 1px solid #e4e6eb;">
            <!-- <div class="d-flex align-items-center justify-content-between px-3 pt-4 pb-2">
                <span class="fw-bold fs-4">Marketplace</span>
                <button class="btn btn-light btn-sm rounded-circle" type="button" aria-label="Marketplace settings">
                    <i class="bi bi-gear"></i>
                </button>
            </div> -->
            <!-- <div class="px-3 mb-2">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-0 bg-light" placeholder="Search Marketplace" aria-label="Search Marketplace">
                </div>
            </div> -->
            <nav class="nav flex-column mb-2">
                <a href="#" class="nav-link d-flex align-items-center px-3 py-2 fw-semibold<?= !empty($categories[0]['active']) ? ' active' : '' ?>">
                    <i class="bi bi-grid fs-5 me-3"></i> Browse all
                </a>
                <a href="#" class="nav-link d-flex align-items-center px-3 py-2<?= !empty($categories[1]['active']) ? ' active' : '' ?>">
                    <i class="bi bi-bag fs-5 me-3"></i> Buying
                </a>
                <a href="#" class="nav-link d-flex align-items-center px-3 py-2<?= !empty($categories[2]['active']) ? ' active' : '' ?>">
                    <i class="bi bi-tags fs-5 me-3"></i> Selling
                </a>
                <a href="<?= $base_url ?>/public/quote_request.php" class="nav-link d-flex align-items-center px-3 py-2">
                    <i class="bi bi-plus-circle fs-5 me-3"></i> Create new listing
                </a>
            </nav>
            <hr class="my-2">
            <div class="px-3 mb-2">
                <div class="text-muted small mb-1">Filters</div>
                <a class="d-flex align-items-center text-decoration-none mb-2" href="#">
                    <i class="bi bi-geo-alt-fill me-2"></i>
                    <span><?= htmlspecialchars($location_label) ?></span>
                </a>
            </div>
            <hr class="my-2">
            <div class="px-3">
                <div class="text-muted small mb-1">Categories</div>
                <nav class="nav flex-column">
                    <a href="#" class="nav-link d-flex align-items-center px-0 py-2">
                        <i class="bi bi-car-front fs-5 me-3"></i> Vehicles
                    </a>
                    <a href="#" class="nav-link d-flex align-items-center px-0 py-2">
                        <i class="bi bi-house-door fs-5 me-3"></i> Property Rentals
                    </a>
                    <a href="#" class="nav-link d-flex align-items-center px-0 py-2">
                        <i class="bi bi-phone fs-5 me-3"></i> Electronics
                    </a>
                    <a href="#" class="nav-link d-flex align-items-center px-0 py-2">
                        <i class="bi bi-controller fs-5 me-3"></i> Toys & Games
                    </a>
                </nav>
            </div>
            <div class="mt-auto px-3 pb-3 small text-muted" style="position: absolute; bottom: 0;">
                <a href="#" class="text-muted">Privacy</a> · <a href="#" class="text-muted">Terms</a> · <a href="#" class="text-muted">Cookies</a><br>
                &copy; <?= date('Y') ?> ShopMVP
            </div>
        </aside>

        <!-- Main content -->
        <main class="mp-main col-12 col-lg-9 col-xl-10 ms-auto px-0" style="background: #f0f2f5; min-height: 100vh;">
            <div class="mp-content mp-content-tight px-3 pt-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Could not load products. Please check your database connection.<br>
                        <small class="text-muted"><?= htmlspecialchars($error) ?></small>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="fw-bold mb-0" style="font-size: 1.5rem;">Today's picks</h2>
                        <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-sm" style="font-size: 0.95rem;">
                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                            <span><?= htmlspecialchars($location_label) ?></span>
                        </div>
                    </div>
                    <?php if (empty($products)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>No products found.
                            Import <code>sql/schema.sql</code> to add seed data.
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-6 g-3">
                            <?php foreach ($products as $i => $product): ?>
                                <?php $inStock = (int) $product['stock'] > 0; ?>
                                <div class="col fade-up" style="animation-delay:<?= min($i * 0.05, 0.4) ?>s">
                                    <a href="product.php?id=<?= (int) $product['id'] ?>" class="text-decoration-none">
                                        <div class="card product-card h-100<?= $inStock ? '' : ' out-of-stock' ?>" style="box-shadow: 0 2px 8px rgba(0,0,0,0.07); border: none; border-radius: 10px; transition: all 0.3s ease; min-width: 0;">
                                            <div class="card-img-wrapper" style="background: #f0f2f5; min-height: 120px; display: flex; align-items: center; justify-content: center; border-radius: 10px 10px 0 0;">
                                                <div class="card-img-placeholder" style="font-size: 2rem; color: #6c757d;">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                            </div>
                                            <div class="card-body" style="padding: 10px;">
                                                <span class="price" style="font-size: 1.1rem; font-weight: 700; color: #050505;">
                                                    $<?= number_format((float) $product['price'], 2) ?>
                                                </span>
                                                <h5 class="card-title mb-1" style="font-size: 0.95rem; font-weight: 600; margin-top: 6px; line-height: 1.3; color: #050505;">
                                                    <?= htmlspecialchars($product['name']) ?>
                                                </h5>
                                                <div class="mp-card-sub" style="margin: 6px 0;">
                                                    <span class="mp-card-location" style="font-size: 0.8rem; color: #65676b;">Kampala, Uganda</span>
                                                </div>
                                                <span class="card-stock-badge <?= $inStock ? 'in-stock' : 'sold-out' ?>" style="display: inline-block; padding: 4px 10px; border-radius: 16px; font-size: 0.7rem; font-weight: 600; background-color: <?= $inStock ? '#e7f3e3' : '#f8d7da' ?>; color: <?= $inStock ? '#1877f2' : '#842029' ?>;">
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
            </div>
        </main>
    </div>
</div>

<?php 
    // require_once __DIR__ . '/../app/includes/footer.php'; 
    ?>