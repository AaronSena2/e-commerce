<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';
// Add product to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $pid = intval($_POST['product_id']);
    $_SESSION['rfq_cart'][$pid] = ($_SESSION['rfq_cart'][$pid] ?? 0) + 1;
}
// Handle update or remove
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $pid => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['rfq_cart'][$pid]);
        } else {
            $_SESSION['rfq_cart'][$pid] = intval($qty);
        }
    }
}
// RFQ submit handler
$rfq_sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rfq'])) {
    $stmt = $pdo->prepare("INSERT INTO clients (name, email, phone, company) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['company'] ?? '']);
    $client_id = $pdo->lastInsertId();
    $cart = $_SESSION['rfq_cart'] ?? [];
    $ids = array_keys($cart);
    if (count($ids) > 0) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $in = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
        $in->execute($ids);
        $products = [];
        $total = 0;
        foreach ($in->fetchAll() as $row) {
            $products[$row['id']] = $row['price'];
            $total += $row['price'] * $cart[$row['id']];
        }
        $stmt = $pdo->prepare("INSERT INTO quotations (client_id, status, total) VALUES (?, 'pending', ?)");
        $stmt->execute([$client_id, $total]);
        $qid = $pdo->lastInsertId();
        $itemStmt = $pdo->prepare("INSERT INTO quotation_items (quotation_id, product_id, quantity) VALUES (?, ?, ?)");
        foreach ($cart as $pid => $qty) {
            $itemStmt->execute([$qid, $pid, $qty]);
        }
        $rfq_sent = true;
        unset($_SESSION['rfq_cart']);
    }
}
?>
<div class="container my-5">
  <h2 class="mb-4 text-center">Your Quotation Cart</h2>
  <?php if($rfq_sent): ?>
    <div class="alert alert-success">Quotation request sent. We'll contact you soon!</div>
  <?php endif; ?>
  <?php
  $cart = $_SESSION['rfq_cart'] ?? [];
  if (empty($cart)): ?>
    <div class="alert alert-info text-center">Your cart is empty. <a href="shop.php">Browse Products</a></div>
  <?php else:
    $ids = array_keys($cart);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")");
    $stmt->execute($ids);
    $products = [];
    $total = 0;
    foreach ($stmt as $p) {
        $products[$p['id']] = $p;
        $total += $p['price'] * $cart[$p['id']];
    }
  ?>
  <form method="post">
    <table class="table table-bordered align-middle">
      <thead><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Line Total</th></tr></thead>
      <tbody>
      <?php foreach ($cart as $pid => $qty): ?>
        <tr>
            <td><?= htmlspecialchars($products[$pid]['name']) ?></td>
            <td><input type="number" name="qty[<?= $pid ?>]" value="<?= $qty ?>" min="1" max="<?= $products[$pid]['stock']?>" class="form-control" style="width:80px;"></td>
            <td>UGX <?= number_format($products[$pid]['price'],0) ?></td>
            <td>UGX <?= number_format($products[$pid]['price'] * $qty,0) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot><tr><th colspan="3" class="text-end">Total:</th><th>UGX <?= number_format($total,0) ?></th></tr></tfoot>
    </table>
    <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
  </form>
  <h3 class="mt-4">Request Quotation</h3>
  <form method="post" class="row g-3">
    <div class="col-md-6">
      <input name="name" class="form-control" placeholder="Your Name" required>
    </div>
    <div class="col-md-6">
      <input name="email" type="email" class="form-control" placeholder="Your Email" required>
    </div>
    <div class="col-md-6">
      <input name="phone" class="form-control" placeholder="Phone Number">
    </div>
    <div class="col-md-6">
      <input name="company" class="form-control" placeholder="Company (Optional)">
    </div>
    <div class="col-12">
      <button type="submit" name="submit_rfq" class="btn btn-primary btn-lg mt-2">Send Request</button>
    </div>
  </form>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>