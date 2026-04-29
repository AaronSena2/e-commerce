<?php
/**
 * quote_success.php — Confirmation page shown after a successful quote request.
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/lib/session.php';
require_once __DIR__ . '/../app/models/QuoteRequest.php';

$request_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$request = null;
if ($request_id && $request_id > 0) {
    try {
        $request = quote_request_get_by_id($request_id);
    } catch (RuntimeException $e) {
        $request = null;
    }
}

$page_title = 'Quote Request Received — ShopMVP';
require_once __DIR__ . '/../app/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center py-5 fade-up">

            <div class="success-icon-wrap">
                <i class="bi bi-envelope-check"></i>
            </div>

            <h1 class="h3 fw-bold mb-3" style="letter-spacing:-.02em;">Quote Request Received!</h1>

            <?php if ($request): ?>
                <p class="text-muted mb-1">
                    Your request for
                    <strong><?= htmlspecialchars($request['product_name'] ?? 'the product') ?></strong>
                    has been submitted successfully.
                </p>
                <p class="text-muted mb-5">
                    We will contact you at
                    <strong><?= htmlspecialchars($request['email']) ?></strong>.
                </p>
            <?php else: ?>
                <p class="text-muted mb-5">
                    Your quote request has been submitted. We will be in touch soon.
                </p>
            <?php endif; ?>

            <div class="d-flex justify-content-center gap-2">
                <a href="index.php" class="btn btn-primary px-4">
                    <i class="bi bi-grid me-1"></i>Continue Shopping
                </a>
                <?php if ($request): ?>
                    <a href="product.php?id=<?= (int) $request['product_id'] ?>"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Product
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div><!-- /container -->

<?php require_once __DIR__ . '/../app/includes/footer.php'; ?>
