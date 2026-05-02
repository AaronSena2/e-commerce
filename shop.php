<?php
include 'includes/header.php';
require 'includes/db.php';
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>
<div class="container my-5">
  <h2 class="mb-4 text-center">Product Catalog</h2>
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow h-100">
          <img src="uploads/<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" style="height:250px; object-fit:cover;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <div class="mt-auto">
              <span class="badge bg-info fs-6 mb-2">UGX <?= number_format($product['price'], 0) ?></span>
              <?php if($product['stock'] > 0): ?>
                <form action="rfq_cart.php" method="post" class="d-inline">
                  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                  <button type="submit" class="btn btn-success">Add to Quote</button>
                </form>
                <span class="badge bg-secondary ms-2"><?= $product['stock']?> In Stock</span>
              <?php else: ?>
                <span class="badge bg-danger">Out of Stock</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="text-center">
    <a href="rfq_cart.php" class="btn btn-warning btn-lg mt-3">View Quotation Cart</a>
  </div>
</div>
<?php include 'includes/footer.php'; ?>