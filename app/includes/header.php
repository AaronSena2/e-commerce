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

    <!-- Google Fonts — Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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
     Navbar — Facebook Marketplace style
     ================================================================ -->
<nav class="navbar navbar-expand-lg sticky-top" style="background-color:#0078D4;">
    <div class="container-fluid px-0">
        <div class="d-flex align-items-center w-100" style="min-height:56px;">
            <!-- Left: Brand/Logo -->
            <a class="navbar-brand d-flex align-items-center px-2 gap-2 flex-shrink-0" href="<?= $base_url ?>/public/index.php" style="font-weight:700;font-size:1.5rem;">
                <i class="bi bi-shop-window fs-3 text-primary"></i>
                <span class="d-none d-md-inline text-dark">Marketplace</span>
            </a>

            <!-- Center: Navigation icons -->
            <ul class="nav mx-2 flex-nowrap align-items-center" style="gap:0.5rem;">
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex flex-column align-items-center justify-content-center active" href="<?= $base_url ?>/public/index.php" style="min-width:56px;">
                        <i class="bi bi-shop fs-4"></i>
                        <span class="small d-none d-md-block">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex flex-column align-items-center justify-content-center" href="<?= $base_url ?>/public/admin/quotes.php" style="min-width:56px;">
                        <i class="bi bi-chat-quote fs-4"></i>
                        <span class="small d-none d-md-block">Quotes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-3 d-flex flex-column align-items-center justify-content-center" href="<?= $base_url ?>/public/admin/index.php" style="min-width:56px;">
                        <i class="bi bi-speedometer2 fs-4"></i>
                        <span class="small d-none d-md-block">Admin</span>
                    </a>
                </li>
            </ul>

            <!-- Center: Search bar -->
            <form class="d-none d-lg-flex align-items-center ms-3 flex-grow-1" role="search" style="max-width:400px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-0 bg-light" placeholder="Search Marketplace" aria-label="Search">
                </div>
            </form>

            <!-- Right: Cart and profile -->
            <div class="d-flex align-items-center ms-auto gap-2 pe-2">
                <a href="<?= $base_url ?>/public/cart.php" class="btn btn-light position-relative d-flex align-items-center justify-content-center rounded-circle" style="width:40px;height:40px;">
                    <i class="bi bi-cart3 fs-5"></i>
                    <?php $count = cart_count(); if ($count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge" style="font-size:0.7rem;">
                            <?= $count ?>
                        </span>
                    <?php endif; ?>
                </a>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center justify-content-center rounded-circle bg-light" style="width:40px;height:40px;" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-4 text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><a class="dropdown-item" href="<?= $base_url ?>/public/admin/index.php"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</a></li>
                        <li><a class="dropdown-item" href="<?= $base_url ?>/public/admin/quotes.php"><i class="bi bi-chat-quote me-2"></i>Quote Requests</a></li>
                    </ul>
                </div>
            </div>
            <!-- Mobile search toggle -->
            <button class="btn d-lg-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-controls="mobileSearch" aria-expanded="false" aria-label="Toggle search">
                <i class="bi bi-search fs-5"></i>
            </button>
        </div>
        <!-- Mobile search bar -->
        <div class="collapse" id="mobileSearch">
            <form class="d-flex align-items-center px-3 py-2" role="search">
                <div class="input-group w-100">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-0 bg-light" placeholder="Search Marketplace" aria-label="Search">
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- Page content starts here -->
<main class="<?= htmlspecialchars($main_class ?? 'py-4') ?>">
