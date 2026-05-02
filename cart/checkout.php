<?php
include "../config/db.php";
include "../includes/header.php";
include "../includes/navbar.php";
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo '<div class="container mt-5"><h4>No items in cart.</h4></div>';
    include "../includes/footer.php";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id=".intval($product_id));
        $product = mysqli_fetch_assoc($result);
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
    }
    mysqli_query($conn, "INSERT INTO orders (user_id, total, status) VALUES (NULL, '$total', 'Pending')");
    $order_id = mysqli_insert_id($conn);
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id=".intval($product_id));
        $product = mysqli_fetch_assoc($result);
        $price = $product['price'];
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$qty', '$price')");
    }
    unset($_SESSION['cart']);
    header("Location: checkout.php?success=1");
    exit();
}
?>
<div class="container mt-5">
  <h3>Checkout</h3>
  <?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">Order placed successfully!</div>
  <?php else: ?>
  <form method="POST">
    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Delivery Address</label>
      <input type="text" name="address" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Place Order</button>
  </form>
  <?php endif; ?>
</div>
<?php include "../includes/footer.php"; ?>
