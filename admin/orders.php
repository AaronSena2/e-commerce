<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../users/login.php');
    exit();
}
include "../config/db.php";
include "../includes/header.php";
include "../includes/navbar.php";
$result = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC");
?>
<div class="container mt-5">
<h2>Manage Orders</h2>
<table class="table table-bordered">
<thead><tr><th>ID</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
<tbody>
<?php while($order = mysqli_fetch_assoc($result)): ?>
<tr>
  <td><?=$order['id']?></td>
  <td>$<?=number_format($order['total'],2)?></td>
  <td><?=$order['status']?></td>
  <td><?=$order['created_at']?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<?php include "../includes/footer.php"; ?>
