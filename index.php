<?php include 'includes/header.php'; ?>
<section class="hero bg-primary text-white text-center p-5" style="background:linear-gradient(110deg,#1a2980,#26d0ce)">
  <div class="container">
    <h1 class="display-4">ROSA-TECH ENGINEERING LTD</h1>
    <p class="lead">Your Trusted Engineering Supplier in Namuwongo</p>
    <a href="shop.php" class="btn btn-lg btn-warning mt-4 btn-fancy me-2">Shop Now</a>
    <?php if (empty($_SESSION['user_id'])): ?>
      <a href="users/login.php" class="btn btn-lg btn-outline-light mt-4 me-2">Login</a>
      <a href="users/register.php" class="btn btn-lg btn-light mt-4">Register</a>
    <?php else: ?>
      <a href="rfq_cart.php" class="btn btn-lg btn-outline-light mt-4">View Cart</a>
    <?php endif; ?>
  </div>
</section>
<?php include 'includes/footer.php'; ?>