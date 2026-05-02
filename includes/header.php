<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ROSA-TECH ENGINEERING LTD</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-0">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">ROSA-TECH</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a href="/shop.php" class="nav-link">Shop</a></li>
        <li class="nav-item"><a href="/rfq_cart.php" class="nav-link">RFQ Cart</a></li>
        <?php if (!empty($_SESSION['user_id'])): ?>
          <?php if (!empty($_SESSION['is_admin'])): ?>
            <li class="nav-item"><a href="/admin/dashboard.php" class="nav-link">Admin</a></li>
          <?php endif; ?>
          <li class="nav-item"><a href="/users/logout.php" class="nav-link">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a href="/users/login.php" class="nav-link">Login</a></li>
          <li class="nav-item"><a href="/users/register.php" class="nav-link">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
