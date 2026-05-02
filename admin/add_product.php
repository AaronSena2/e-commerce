<?php
require '../includes/db.php';
require '../includes/auth.php';
require_admin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_name = '';

    if(isset($_FILES['image']) && $_FILES['image']['error']==0) {
        $target_dir = '../uploads/';
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid('prod_', true) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url, stock) VALUES (?,?,?,?,?)");
    $stmt->execute([$name, $desc, $price, $image_name, $stock]);
    $message = 'Product added!';
}

include '../includes/header.php';
?>
<div class="container my-4">
  <h2>Add Product</h2>
  <?php if($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="w-50">
    <input name="name" required class="form-control mb-2" placeholder="Name">
    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
    <input name="price" type="number" step="0.01" required class="form-control mb-2" placeholder="Price">
    <input name="stock" type="number" min="0" value="0" required class="form-control mb-2" placeholder="Initial Stock">
    <label>Image: <input type="file" name="image" required class="form-control mb-2"></label>
    <button class="btn btn-success">Add Product</button>
  </form>
</div>
<?php include '../includes/footer.php'; ?>
