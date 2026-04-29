<?php
/**
 * Header partial — included at the top of every public page.
 *
 * Expects these variables to be defined before inclusion:
 *   $page_title (string) – used in <title>
 *
 * Requires session.php to have been loaded already (for cart_count()).
 */

// Determine root-relative base path so assets work regardless of sub-folder depth.
// Pages in /public/ are one level up; admin pages (/public/admin/) pre-set $base_url
// before including this file, so we skip recomputing it when it is already set.
if (!isset($base_url)) {
    $base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/..';
    // Normalize double-slashes just in case
    $base_url = preg_replace('#/+#', '/', $base_url);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'E-Commerce Shop') ?></title>

    <!-- Bootstrap 5 CSS (CDN) -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- Custom styles -->
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/styles.css">
</head>
<body>

<!-- ================================================================
     Navbar
     ================================================================ -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= $base_url ?>/public/index.php">
            <i class="bi bi-shop me-1"></i> ShopMVP
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMain"
                aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>/public/index.php">
                        <i class="bi bi-grid me-1"></i>Products
                    </a>
                </li>
            </ul>

            <!-- Right side: Admin link + Cart -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-2">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-speedometer2 me-1"></i>Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?= $base_url ?>/public/admin/index.php">
                                <i class="bi bi-house me-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= $base_url ?>/public/admin/quotes.php">
                                <i class="bi bi-chat-quote me-2"></i>Quote Requests
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Cart icon with item count badge -->
            <a href="<?= $base_url ?>/public/cart.php" class="btn btn-outline-light position-relative">
                <i class="bi bi-cart3"></i>
                <?php
                $count = cart_count();
                if ($count > 0):
                ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                        <?= $count ?>
                    </span>
                <?php endif; ?>
                <span class="ms-1 d-none d-sm-inline">Cart</span>
            </a>
        </div>
    </div>
</nav>

<!-- Page content starts here -->
<main class="py-4">
