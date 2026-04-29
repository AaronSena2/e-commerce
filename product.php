<?php
/**
 * product.php — Facebook Marketplace-style product detail page
 *
 * Add-to-cart:  POST to this page with product_id + quantity.
 * Out-of-stock: quantity form is hidden; "Request a Quote" remains.
 */

session_start();

/* ------------------------------------------------------------------
   Add-to-cart handler (POST)
   ------------------------------------------------------------------ */
$cart_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $qty        = max(1, min((int)($_POST['quantity'] ?? 1), 99));

    if ($product_id > 0) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + $qty;
        $cart_message = 'Item added to cart!';
    }
}

/* ------------------------------------------------------------------
   Product data
   In a real app, fetch from the database using the ?id= query param.
   ------------------------------------------------------------------ */
$product_id = (int)($_GET['id'] ?? 1);

$product = [
    'id'          => $product_id,
    'name'        => 'Wireless Noise-Cancelling Headphones',
    'price'       => 89.99,
    'currency'    => '$',
    'stock'       => 12,
    'location'    => 'Nairobi, Kenya',
    'listed'      => 'Listed 2 days ago',
    'condition'   => 'New',
    'category'    => 'Electronics',
    'description' => 'Premium wireless headphones featuring active noise cancellation, '
                   . '30-hour battery life, foldable design for travel, and deep bass sound. '
                   . 'Compatible with all Bluetooth 5.0 devices. Includes carrying pouch and USB-C cable.',
    'image'       => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
];

$in_stock   = $product['stock'] > 0;
$cart_count = array_sum($_SESSION['cart'] ?? []);
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['name']) ?> — Shop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- ----------------------------------------------------------------
     Breadcrumb bar
     ---------------------------------------------------------------- -->
<nav class="mp-breadcrumb" aria-label="breadcrumb">
  <div class="container">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item">
        <a href="index.php"><i class="bi bi-shop me-1"></i>Marketplace</a>
      </li>
      <li class="breadcrumb-item">
        <a href="category.php?c=<?= urlencode($product['category']) ?>">
          <?= htmlspecialchars($product['category']) ?>
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        <?= htmlspecialchars($product['name']) ?>
      </li>
    </ol>
  </div>
</nav>

<!-- ----------------------------------------------------------------
     Main content
     ---------------------------------------------------------------- -->
<main class="container py-4">

  <?php if ($cart_message): ?>
  <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    <i class="bi bi-cart-check-fill me-2"></i><?= htmlspecialchars($cart_message) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <div class="row g-4 align-items-start fade-up">

    <!-- ---- Product image ---------------------------------------- -->
    <div class="col-12 col-md-7">
      <div class="mp-image-card">
        <img src="<?= htmlspecialchars($product['image']) ?>"
             alt="<?= htmlspecialchars($product['name']) ?>"
             class="product-detail-img">
      </div>
    </div>

    <!-- ---- Details panel --------------------------------------- -->
    <div class="col-12 col-md-5">
      <div class="mp-details-panel">

        <!-- Title -->
        <h1 class="mp-product-title"><?= htmlspecialchars($product['name']) ?></h1>

        <!-- Price -->
        <div class="price-tag">
          <?= htmlspecialchars($product['currency']) ?><?= number_format($product['price'], 2) ?>
        </div>

        <!-- Location / listed-date metadata -->
        <?php if (!empty($product['location'])): ?>
        <div class="mp-meta-row">
          <span class="mp-meta-item">
            <i class="bi bi-geo-alt-fill"></i>
            <?= htmlspecialchars($product['location']) ?>
          </span>
          <?php if (!empty($product['listed'])): ?>
          <span class="mp-meta-item ms-3">
            <i class="bi bi-clock"></i>
            <?= htmlspecialchars($product['listed']) ?>
          </span>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Condition + stock badges -->
        <div class="mp-badges">
          <?php if (!empty($product['condition'])): ?>
          <span class="badge mp-badge-pill mp-badge-condition">
            <?= htmlspecialchars($product['condition']) ?>
          </span>
          <?php endif; ?>

          <?php if ($in_stock): ?>
          <span class="badge mp-badge-pill mp-badge-instock">
            <i class="bi bi-check-circle-fill"></i>
            In Stock (<?= (int)$product['stock'] ?>)
          </span>
          <?php else: ?>
          <span class="badge mp-badge-pill mp-badge-outofstock">
            <i class="bi bi-x-circle-fill"></i>
            Out of Stock
          </span>
          <?php endif; ?>
        </div>

        <hr class="mp-divider">

        <!-- Description -->
        <p class="mp-description">
          <?= nl2br(htmlspecialchars($product['description'])) ?>
        </p>

        <hr class="mp-divider">

        <!-- ---- Action area ------------------------------------ -->
        <?php if ($in_stock): ?>
        <form method="post"
              action="product.php?id=<?= (int)$product['id'] ?>"
              novalidate>
          <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

          <!-- Quantity stepper -->
          <div class="mp-qty-row mb-3">
            <label for="quantity" class="mp-qty-label">Quantity</label>
            <div class="mp-qty-stepper" role="group" aria-label="Quantity selector">
              <button type="button"
                      class="mp-qty-btn"
                      id="qty-dec"
                      aria-label="Decrease quantity">
                <i class="bi bi-dash"></i>
              </button>
              <input type="number"
                     id="quantity"
                     name="quantity"
                     class="mp-qty-input"
                     value="1"
                     min="1"
                     max="<?= (int)$product['stock'] ?>"
                     aria-label="Product quantity">
              <button type="button"
                      class="mp-qty-btn"
                      id="qty-inc"
                      aria-label="Increase quantity">
                <i class="bi bi-plus"></i>
              </button>
            </div>
          </div>

          <!-- Add to Cart -->
          <button type="submit"
                  name="add_to_cart"
                  value="1"
                  class="btn mp-btn-primary w-100 mb-2">
            <i class="bi bi-cart-plus me-2"></i>Add to Cart
          </button>

          <!-- View Cart -->
          <a href="cart.php"
             class="btn mp-btn-secondary w-100 mb-2">
            <i class="bi bi-cart me-2"></i>View Cart
            <?php if ($cart_count > 0): ?>
            <span class="badge bg-primary ms-1"><?= $cart_count ?></span>
            <?php endif; ?>
          </a>

          <!-- Request a Quote -->
          <a href="quote.php?id=<?= (int)$product['id'] ?>"
             class="btn mp-btn-outline w-100">
            <i class="bi bi-envelope me-2"></i>Request a Quote
          </a>
        </form>

        <?php else: /* out-of-stock branch */ ?>

        <div class="mp-oos-notice" role="status">
          <i class="bi bi-bell-fill"></i>
          This item is currently out of stock.
          <a href="notify.php?id=<?= (int)$product['id'] ?>"
             class="mp-notify-link">Notify me when available</a>
        </div>

        <a href="quote.php?id=<?= (int)$product['id'] ?>"
           class="btn mp-btn-outline w-100 mt-3">
          <i class="bi bi-envelope me-2"></i>Request a Quote
        </a>

        <?php endif; ?>

      </div><!-- /.mp-details-panel -->
    </div>

  </div><!-- /.row -->
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* Quantity stepper */
(function () {
  var input  = document.getElementById('quantity');
  if (!input) return;
  var maxVal = parseInt(input.max, 10) || 99;

  document.getElementById('qty-dec').addEventListener('click', function () {
    var v = parseInt(input.value, 10);
    if (v > 1) input.value = v - 1;
  });

  document.getElementById('qty-inc').addEventListener('click', function () {
    var v = parseInt(input.value, 10);
    if (v < maxVal) input.value = v + 1;
  });

  /* Clamp on manual input */
  input.addEventListener('change', function () {
    var v = parseInt(input.value, 10);
    if (isNaN(v) || v < 1)      input.value = 1;
    else if (v > maxVal)        input.value = maxVal;
  });
}());

/* Fade-up entrance animation (progressive enhancement) */
(function () {
  /* Mark body so CSS activates the animation */
  document.body.classList.add('js-animate');

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        observer.unobserve(e.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.fade-up').forEach(function (el) {
    observer.observe(el);
  });
}());
</script>

</body>
</html>
