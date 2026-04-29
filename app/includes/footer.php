<?php
/**
 * Footer partial — included at the bottom of every public page.
 */
?>
</main><!-- /main -->

<footer class="text-center">
    <div class="container">
        <p class="mb-1">&copy; <?= date('Y') ?> ShopMVP &mdash; Built with PHP &amp; Bootstrap</p>
        <p class="mb-0">
            <a href="<?= $base_url ?>/public/index.php">Shop</a>
            &nbsp;&bull;&nbsp;
            <a href="<?= $base_url ?>/public/cart.php">Cart</a>
        </p>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle (CDN) -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmf5MRn9lci2Yw7y2TLXFXgGH0T"
    crossorigin="anonymous">
</script>

<!-- Custom cart JS -->
<script src="<?= $base_url ?>/assets/js/cart.js"></script>
</body>
</html>
