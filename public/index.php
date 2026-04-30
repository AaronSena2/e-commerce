
<style>
    body {
        background: #f4f6fa;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .main-header {
        background: #fff;
        border-bottom: 3px solid #2564cf;
        padding: 1.5rem 0 1rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(37,100,207,0.04);
    }
    .main-header .logo {
        font-size: 2rem;
        font-weight: 700;
        color: #2564cf;
        letter-spacing: 1px;
        text-decoration: none;
    }
    .main-header .search-bar {
        max-width: 420px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        background: #f4f6fa;
        border-radius: 30px;
        border: 1px solid #e3e7ef;
        padding: 0.25rem 1rem;
    }
    .main-header .search-bar input {
        border: none;
        background: transparent;
        outline: none;
        flex: 1;
        padding: 0.5rem 0.5rem 0.5rem 0.5rem;
        font-size: 1rem;
    }
    .main-header .search-bar i {
        color: #2564cf;
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }
    .main-header .nav {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        justify-content: flex-end;
    }
    .main-header .nav a {
        color: #222;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
        font-size: 1rem;
    }
    .main-header .nav a:hover {
        color: #2564cf;
    }
    .product-listing {
        padding: 0 0 2rem 0;
    }
    .product-listing-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 1.5rem;
        letter-spacing: 0.5px;
    }
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.5rem;
    }
    .product-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(37,100,207,0.07);
        transition: box-shadow 0.2s, transform 0.2s;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        min-width: 0;
        border: 1px solid #e3e7ef;
    }
    .product-card:hover {
        box-shadow: 0 6px 24px rgba(37,100,207,0.13);
        transform: translateY(-2px) scale(1.02);
    }
    .product-card .card-img-wrapper {
        background: #f4f6fa;
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
    }
    .product-card .card-img-placeholder {
        font-size: 2.5rem;
        color: #2564cf;
    }
    .product-card .card-body {
        padding: 1rem 1rem 0.75rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .product-card .price {
        font-size: 1.15rem;
        font-weight: 700;
        color: #2564cf;
        margin-bottom: 0.25rem;
    }
    .product-card .card-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #222;
        margin-bottom: 0.25rem;
        margin-top: 0;
        line-height: 1.3;
    }
    .product-card .mp-card-location {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .product-card .card-stock-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 16px;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: #e7f3e3;
        color: #2564cf;
        margin-top: 0.25rem;
    }
    .product-card .card-stock-badge.sold-out {
        background-color: #f8d7da;
        color: #842029;
    }
    .alert {
        margin-top: 1.5rem;
    }
    @media (max-width: 767px) {
        .main-header {
            padding: 1rem 0 0.5rem 0;
        }
        .main-header .logo {
            font-size: 1.3rem;
        }
        .product-listing-title {
            font-size: 1.1rem;
        }
        .product-card .card-img-wrapper {
            min-height: 90px;
        }
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


<!-- Header -->

<header class="main-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" style="border-bottom: 3px solid #2564cf;">
        <div class="container">
            <a class="navbar-brand logo" href="/">ShopMVP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-grid me-1"></i> Categories
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-car-front me-1"></i> Vehicles</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-house-door me-1"></i> Property Rentals</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-phone me-1"></i> Electronics</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-controller me-1"></i> Toys & Games</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> Account
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-bell me-1"></i> Notifications</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-inbox me-1"></i> Inbox</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-shield-lock me-1"></i> Marketplace Access</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="buySellDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bag me-1"></i> Buying/Selling
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="buySellDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-bag me-1"></i> Buying</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-tags me-1"></i> Selling</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/public/quote_request.php"><i class="bi bi-plus-circle me-1"></i> List Product</a>
                    </li>
                </ul>
                <form class="d-flex search-bar" method="get" action="/public/index.php" style="max-width: 420px;">
                    <i class="bi bi-search align-self-center"></i>
                    <input class="form-control border-0 bg-transparent" type="text" name="q" placeholder="Search products..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                </form>
            </div>
        </div>
    </nav>
</header>
<!-- Banner Section -->
<section class="homepage-banner position-relative" style="background: linear-gradient(90deg, #2564cf 0%, #1e3a8a 100%); min-height: 320px; display: flex; align-items: center; justify-content: center; color: #fff;">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-md-7 mb-4 mb-md-0">
                <h1 class="fw-bold display-5 mb-3" style="letter-spacing: -1px;">Welcome to <span style="color: #ffd600;">E-commerce Marketplace</span></h1>
                <p class="lead mb-4" style="max-width: 480px;">Find the best deals on electronics, vehicles, property rentals, and more. Shop, sell, and connect with trusted sellers in your area.</p>
                <a href="/public/quote_request.php" class="btn btn-warning btn-lg fw-semibold px-4 shadow-sm" style="color: #1e3a8a;">List Your Product</a>
                <a href="#products" class="btn btn-outline-light btn-lg fw-semibold px-4 ms-2">Browse Products</a>
            </div>
            <div class="col-md-5 text-center">
                <img src="/assets/images/banner-illustration.svg" alt="Marketplace Banner" class="img-fluid" style="max-height: 220px;">
            </div>
        </div>
    </div>
    <!-- Decorative shapes -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
        <svg width="100%" height="100%" viewBox="0 0 1440 320" fill="none" xmlns="http://www.w3.org/2000/svg" style="position: absolute; bottom: 0; left: 0;">
            <path fill="#fff" fill-opacity="0.07" d="M0,224L48,197.3C96,171,192,117,288,117.3C384,117,480,171,576,197.3C672,224,768,224,864,197.3C960,171,1056,117,1152,117.3C1248,117,1344,171,1392,197.3L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<!-- Main Content -->
<div class="container product-listing">
    <div class="product-listing-title d-flex align-items-center justify-content-between flex-wrap">
        <span>Today's picks</span>
        <span class="d-flex align-items-center" style="font-size: 1rem; color: #2564cf;">
            <i class="bi bi-geo-alt-fill me-2"></i>
            <?= htmlspecialchars($location_label) ?>
        </span>
    </div>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Could not load products. Please check your database connection.<br>
            <small class="text-muted"><?= htmlspecialchars($error) ?></small>
        </div>
    <?php else: ?>
        <?php if (empty($products)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>No products found.
                Import <code>sql/schema.sql</code> to add seed data.
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $i => $product): ?>
                    <?php $inStock = (int) $product['stock'] > 0; ?>
                    <a href="product.php?id=<?= (int) $product['id'] ?>" class="product-card text-decoration-none">
                        <div class="card-img-wrapper">
                            <div class="card-img-placeholder">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <span class="price">
                                $<?= number_format((float) $product['price'], 2) ?>
                            </span>
                            <div class="card-title">
                                <?= htmlspecialchars($product['name']) ?>
                            </div>
                            <span class="mp-card-location">Kampala, Uganda</span>
                            <span class="card-stock-badge<?= $inStock ? '' : ' sold-out' ?>">
                                <?= $inStock ? 'Available' : 'Sold Out' ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php 
    // require_once __DIR__ . '/../app/includes/footer.php'; 
    ?>