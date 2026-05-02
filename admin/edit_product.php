<?php
require '../includes/db.php';
require '../includes/auth.php';
require_admin();
$id = intval($_GET['id'] ?? 0);
$product = $pdo->prepare("SELECT * FROM products WHERE id=?");
$product->execute([$id]);
$product = $product->fetch();
if (!$product) die('Product not found.');

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_name = $product['image_url'];
    if(isset($_FILES['image']) && $_FILES['image']['error']==0) {
        $target_dir = '../uploads/';
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid('prod_', true) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }
    $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image_url=? WHERE id=?");
    $stmt->execute([$name, $desc, $price, $stock, $image_name, $id]);
    $message = "Product updated!";
}

include '../includes/header.php';
?>
<div class="container my-4">
  <h2>Edit Product</h2>
  <?php if($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="w-50">
    <input name="name" required class="form-control mb-2" value="<?= htmlspecialchars($product['name']) ?>">
    <textarea name="description" class="form-control mb-2"><?= htmlspecialchars($product['description']) ?></textarea>
    <input name="price" type="number" step="0.01" required class="form-control mb-2" value="<?= htmlspecialchars($product['price']) ?>">
    <input name="stock" type="number" min="0" required class="form-control mb-2" value="<?= htmlspecialchars($product['stock']) ?>">
    <label>Image: (leave blank to keep current) <input type="file" name="image" class="form-control mb-2"></label>
    <?php if($product['image_url']): ?>
      <img src="/uploads/<?= htmlspecialchars($product['image_url']) ?>" class="img-thumbnail mb-2" width="200">
    <?php endif; ?>
    <button class="btn btn-primary">Update Product</button>
  </form>
</div>
<?php include '../includes/footer.php'; ?>
