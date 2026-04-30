<?php
include "../config/db.php";
include "../includes/header.php";
include "../includes/navbar.php";

if (!isset($_GET['id'])) die("Product not found.");

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id=$id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
if (!$product) die("Product not found.");
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="../assets/images/<?= htmlspecialchars($product['image']) ?>" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <p><strong>Price: $<?= number_format($product['price'],2) ?></strong></p>
            <button class="btn btn-success add-to-cart" data-id="<?= $product['id'] ?>">Add to Cart</button>
        </div>
    </div>
</div>
<script src="../assets/js/cart.js"></script>
<?php include "../includes/footer.php"; ?>
