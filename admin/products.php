<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../users/login.php');
    exit();
}
include "../config/db.php";
include "../includes/header.php";
include "../includes/navbar.php";
// List products
$result = mysqli_query($conn, "SELECT * FROM products");
?>
<div class="container mt-5">
  <h2>Manage Products</h2>
  <a href="add_product.php" class="btn btn-success mb-2">Add Product</a>
  <table class="table table-bordered">
    <thead><tr><th>Name</th><th>Price</th><th>Stock</th><th>Action</th></tr></thead>
    <tbody>
    <?php while($product = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?=htmlspecialchars($product['name'])?></td>
        <td>$<?=number_format($product['price'],2)?></td>
        <td><?=$product['stock']?></td>
        <td>
          <a href="edit_product.php?id=<?=$product['id']?>" class="btn btn-sm btn-info">Edit</a>
          <a href="delete_product.php?id=<?=$product['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include "../includes/footer.php"; ?>
