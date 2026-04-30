<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../users/login.php');
    exit();
}
include "../includes/header.php";
include "../includes/navbar.php";
?>
<div class="container mt-5">
  <h2>Admin Dashboard</h2>
  <ul>
    <li><a href="products.php">Manage Products</a></li>
    <li><a href="orders.php">Manage Orders</a></li>
  </ul>
</div>
<?php include "../includes/footer.php"; ?>
