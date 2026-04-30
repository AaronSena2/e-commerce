<?php
/**
 * Footer partial — included at the bottom of every public page.
 */
?>
</main><!-- /main -->

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-lg-4">
                <div class="footer-brand">
                    <i class="bi bi-bag-heart-fill me-1"></i>ShopMVP
                </div>
                <p class="footer-tagline">Your modern, no-fuss online store.<br>Fast, simple &amp; beautiful.</p>
            </div>

            <!-- Quick Links -->
            <div class="col-sm-6 col-lg-2">
                <p class="footer-heading">Shop</p>
                <a href="<?= $base_url ?>/public/index.php"><i class="bi bi-grid me-1"></i>All Products</a>
                <a href="<?= $base_url ?>/public/cart.php"><i class="bi bi-cart3 me-1"></i>Cart</a>
                <a href="<?= $base_url ?>/public/checkout.php"><i class="bi bi-credit-card me-1"></i>Checkout</a>
            </div>

            <!-- Admin -->
            <div class="col-sm-6 col-lg-2">
                <p class="footer-heading">Admin</p>
                <a href="<?= $base_url ?>/public/admin/index.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                <a href="<?= $base_url ?>/public/admin/quotes.php"><i class="bi bi-chat-quote me-1"></i>Quote Requests</a>
            </div>

            <!-- Info -->
            <div class="col-sm-6 col-lg-4">
                <p class="footer-heading">Info</p>
                <p style="color:rgba(255,255,255,.4); font-size:.8rem; line-height:1.7;">
                    Built with PHP, MySQL &amp; Bootstrap 5.<br>
                    Designed for XAMPP — no build step required.
                </p>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 footer-bottom">
            <span>&copy; <?= date('Y') ?> ShopMVP. All rights reserved.</span>
            <span>Built with <i class="bi bi-heart-fill" style="color:#ec4899;"></i> &amp; Bootstrap 5</span>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom cart JS -->
<script src="<?= $base_url ?>/assets/js/cart.js"></script>
</body>
</html>
