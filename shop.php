<?php
include "config/db.php";
include "includes/header.php";
include "includes/navbar.php";

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>
<div class="container mt-4">
    <div class="row">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="assets/images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars(substr($row['description'], 0, 100)) ?>...</p>
                    <p class="card-text"><strong>$<?= number_format($row['price'], 2) ?></strong></p>
                    <p class="card-text">
                        <?php if (isset($row['stock']) && $row['stock'] > 0): ?>
                            <span class="text-success">In Stock: <?= intval($row['stock']) ?></span>
                        <?php else: ?>
                            <span class="text-danger">Out of Stock</span>
                        <?php endif; ?>
                    </p>
                    <a href="products/product_details.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary">Details</a>
                    <button class="btn btn-success add-to-cart" data-id="<?= $row['id'] ?>" <?= (!isset($row['stock']) || $row['stock'] <= 0) ? 'disabled' : '' ?>>Add to Cart</button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<script src="assets/js/cart.js"></script>
<?php include "includes/footer.php"; ?>
