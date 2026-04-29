<?php
/**
 * quote_request.php — Form for clients to request a product quotation.
 *
 * Accepts ?product_id=  to pre-load the relevant product.
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';
require_once __DIR__ . '/../app/models/Product.php';
require_once __DIR__ . '/../app/models/QuoteRequest.php';

// ------------------------------------------------------------------
// Validate product_id
// ------------------------------------------------------------------
$product_id = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);

if (!$product_id || $product_id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $product = product_get_by_id($product_id);
} catch (RuntimeException $e) {
    $product = null;
}

if ($product === null) {
    header('Location: index.php');
    exit;
}

// ------------------------------------------------------------------
// Handle form submission
// ------------------------------------------------------------------
$errors      = [];
$form_values = ['name' => '', 'email' => '', 'phone' => '', 'quantity' => '1', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quote'])) {
    // Collect & sanitise raw input
    $form_values['name']     = trim($_POST['name']     ?? '');
    $form_values['email']    = trim($_POST['email']    ?? '');
    $form_values['phone']    = trim($_POST['phone']    ?? '');
    $form_values['quantity'] = trim($_POST['quantity'] ?? '1');
    $form_values['message']  = trim($_POST['message']  ?? '');

    // Validate email (required)
    if ($form_values['email'] === '') {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($form_values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // Validate quantity (optional; if provided must be int >= 1)
    $qty = null;
    if ($form_values['quantity'] !== '') {
        $qty = filter_var($form_values['quantity'], FILTER_VALIDATE_INT);
        if ($qty === false || $qty < 1) {
            $errors[] = 'Quantity must be a whole number of 1 or more.';
            $qty = null;
        }
    }

    if (empty($errors)) {
        try {
            $request_id = quote_request_create([
                'product_id' => (int) $product['id'],
                'name'       => $form_values['name']    !== '' ? $form_values['name']    : null,
                'email'      => $form_values['email'],
                'phone'      => $form_values['phone']   !== '' ? $form_values['phone']   : null,
                'quantity'   => $qty,
                'message'    => $form_values['message'] !== '' ? $form_values['message'] : null,
            ]);
            header('Location: quote_success.php?id=' . $request_id);
            exit;
        } catch (RuntimeException $e) {
            $errors[] = 'Sorry, we could not save your request. Please try again.';
        }
    }
}

$page_title = 'Request a Quote — ' . htmlspecialchars($product['name']);
require_once __DIR__ . '/../app/includes/header.php';
?>

<div class="container">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Products</a></li>
            <li class="breadcrumb-item">
                <a href="product.php?id=<?= (int) $product['id'] ?>">
                    <?= htmlspecialchars($product['name']) ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Request a Quote</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">
                        <i class="bi bi-envelope me-2 text-primary"></i>Request a Quote
                    </h1>
                    <p class="text-muted mb-4">
                        Product: <strong><?= htmlspecialchars($product['name']) ?></strong>
                        &mdash; $<?= number_format((float) $product['price'], 2) ?> each
                    </p>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post"
                          action="quote_request.php?product_id=<?= (int) $product['id'] ?>"
                          novalidate>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                value="<?= htmlspecialchars($form_values['name']) ?>"
                                placeholder="Jane Doe"
                            >
                        </div>

                        <!-- Email (required) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($form_values['email']) ?>"
                                placeholder="you@example.com"
                                required
                            >
                            <div class="form-text">We will contact you at this address.</div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-control"
                                value="<?= htmlspecialchars($form_values['phone']) ?>"
                                placeholder="+1 555 000 0000"
                            >
                        </div>

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity Needed</label>
                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                class="form-control"
                                value="<?= htmlspecialchars($form_values['quantity']) ?>"
                                min="1"
                                style="width:120px;"
                            >
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <label for="message" class="form-label">Message / Additional Requirements</label>
                            <textarea
                                id="message"
                                name="message"
                                class="form-control"
                                rows="4"
                                placeholder="Describe any special requirements, delivery timeline, etc."
                            ><?= htmlspecialchars($form_values['message']) ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="submit_quote" class="btn btn-primary px-4">
                                <i class="bi bi-send me-2"></i>Submit Request
                            </button>
                            <a href="product.php?id=<?= (int) $product['id'] ?>"
                               class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
