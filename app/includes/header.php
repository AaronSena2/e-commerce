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


<!-- Page content starts here -->
<main class="<?= htmlspecialchars($main_class ?? 'py-4') ?>">
