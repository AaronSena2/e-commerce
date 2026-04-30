<?php
include "../includes/header.php";
include "../includes/navbar.php";
session_start();
include "../config/db.php";
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo '<div class="container mt-5"><h4>Your cart is empty.</h4></div>';
    include "../includes/footer.php";
    exit();
}
?>
<div class="container mt-5">
  <h3>Your Cart</h3>
  <table class="table table-bordered">
    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
    <tbody>
      <?php
      $total = 0;
      foreach ($_SESSION['cart'] as $product_id => $qty):
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id=".intval($product_id));
        $product = mysqli_fetch_assoc($result);
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
      ?>
      <tr>
        <td><?=htmlspecialchars($product['name'])?></td>
        <td>$<?=number_format($product['price'],2)?></td>
        <td><?=$qty?></td>
        <td>$<?=number_format($subtotal,2)?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <h4>Total: $<?=number_format($total,2)?></h4>
  <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
</div>
<?php include "../includes/footer.php"; ?>
