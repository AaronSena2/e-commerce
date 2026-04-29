<?php
/**
 * checkout.php — Checkout form; creates order on valid submission.
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';
require_once __DIR__ . '/../app/models/Order.php';

// Redirect to cart if empty
$cart = cart_get();
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

$errors = [];
$old    = [];  // repopulate form on error

// ------------------------------------------------------------------
// Handle POST submission
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect & sanitize input
    $old['name']    = trim($_POST['name']    ?? '');
    $old['email']   = trim($_POST['email']   ?? '');
    $old['phone']   = trim($_POST['phone']   ?? '');
    $old['address'] = trim($_POST['address'] ?? '');
    $old['city']    = trim($_POST['city']    ?? '');
    $old['state']   = trim($_POST['state']   ?? '');
    $old['zip']     = trim($_POST['zip']     ?? '');

    // Basic server-side validation
    if ($old['name'] === '') {
        $errors['name'] = 'Full name is required.';
    }
    if ($old['email'] === '') {
        $errors['email'] = 'Email address is required.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if ($old['address'] === '') {
        $errors['address'] = 'Street address is required.';
    }
    if ($old['city'] === '') {
        $errors['city'] = 'City is required.';
    }
    if ($old['state'] === '') {
        $errors['state'] = 'State / Province is required.';
    }
    if ($old['zip'] === '') {
        $errors['zip'] = 'ZIP / Postal code is required.';
    }

    if (empty($errors)) {
        try {
            $order_id = order_create(
                $old,
                $cart,
                cart_total()
            );
            cart_clear();
            header('Location: order_success.php?order_id=' . $order_id);
            exit;
        } catch (RuntimeException $e) {
            $errors['general'] = 'Could not place your order. Please try again. (' . htmlspecialchars($e->getMessage()) . ')';
        }
    }
}

$total      = cart_total();
$page_title = 'Checkout — ShopMVP';
require_once __DIR__ . '/../app/includes/header.php';
?>

<div class="container">

    <h1 class="h3 mb-4">
        <i class="bi bi-credit-card me-2 text-primary"></i>Checkout
    </h1>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i><?= $errors['general'] ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Checkout form -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Shipping Information</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="checkout.php" id="checkout-form" novalidate>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                                placeholder="Jane Smith"
                                required
                            >
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                placeholder="jane@example.com"
                                required
                            >
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Phone (optional) -->
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Phone <span class="text-muted fw-normal">(optional)</span></label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-control"
                                value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                                placeholder="+1 555 000 1234"
                            >
                        </div>

                        <hr class="my-4">

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Street Address <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="address"
                                name="address"
                                class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['address'] ?? '') ?>"
                                placeholder="123 Main St, Apt 4B"
                                required
                            >
                            <?php if (isset($errors['address'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['address']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row g-3">
                            <!-- City -->
                            <div class="col-sm-5">
                                <label for="city" class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    class="form-control <?= isset($errors['city']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($old['city'] ?? '') ?>"
                                    placeholder="New York"
                                    required
                                >
                                <?php if (isset($errors['city'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['city']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- State -->
                            <div class="col-sm-4">
                                <label for="state" class="form-label fw-semibold">State / Province <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    id="state"
                                    name="state"
                                    class="form-control <?= isset($errors['state']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($old['state'] ?? '') ?>"
                                    placeholder="NY"
                                    required
                                >
                                <?php if (isset($errors['state'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['state']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- ZIP -->
                            <div class="col-sm-3">
                                <label for="zip" class="form-label fw-semibold">ZIP / Postal <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    id="zip"
                                    name="zip"
                                    class="form-control <?= isset($errors['zip']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($old['zip'] ?? '') ?>"
                                    placeholder="10001"
                                    required
                                >
                                <?php if (isset($errors['zip'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['zip']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="bi bi-bag-check me-2"></i>Place Order
                            </button>
                            <a href="cart.php" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="bi bi-arrow-left me-1"></i>Back to Cart
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Order summary sidebar -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Your Order</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cart as $item): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-truncate me-2" style="max-width:230px;">
                                <span class="badge bg-secondary me-1"><?= (int) $item['quantity'] ?>×</span>
                                <?= htmlspecialchars($item['name']) ?>
                            </div>
                            <span class="text-nowrap fw-semibold">
                                $<?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Total</span>
                        <span class="text-success">$<?= number_format($total, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
