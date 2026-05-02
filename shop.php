<?php
include "config/db.php";
include "includes/header.php";
include "includes/navbar.php";

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>
<style>
    .shop-container {
        background: #f8f9fa;
        padding: 40px 0;
        min-height: 80vh;
    }
    .shop-header {
        text-align: center;
        margin-bottom: 40px;
        padding-top: 20px;
    }
    .shop-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-bottom: 40px;
    }
    .product-card {
        background: white;
        border: none;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .product-card:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        transform: translateY(-5px);
    }
    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: #e9ecef;
    }
    .product-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        min-height: 2.4em;
    }
    .product-description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 12px;
        line-height: 1.4;
        flex-grow: 1;
    }
    .product-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: #28a745;
        margin-bottom: 15px;
    }
    .product-actions {
        display: flex;
        gap: 10px;
        margin-top: auto;
    }
    .product-actions .btn {
        flex: 1;
        padding: 10px;
        font-size: 0.95rem;
        font-weight: 500;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    .btn-details {
        background: #007bff;
        color: white;
        border: 1px solid #007bff;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-details:hover {
        background: #0056b3;
        border-color: #0056b3;
        color: white;
        text-decoration: none;
    }
    .btn-cart {
        background: #28a745;
        color: white;
        border: 1px solid #28a745;
        cursor: pointer;
    }
    .btn-cart:hover {
        background: #218838;
        border-color: #218838;
    }
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .shop-header h1 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="shop-container">
    <div class="container">
        <div class="shop-header">
            <h1>Shop</h1>
        </div>
        
        <div class="products-grid">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <img src="assets/images/<?= htmlspecialchars($row['image']) ?>" class="product-image" alt="<?= htmlspecialchars($row['name']) ?>">
                <div class="product-content">
                    <h5 class="product-title"><?= htmlspecialchars($row['name']) ?></h5>
                    <p class="product-description"><?= htmlspecialchars(substr($row['description'], 0, 80)) ?>...</p>
                    <p class="product-price">$<?= number_format($row['price'], 2) ?></p>
                    <div class="product-actions">
                        <a href="products/product_details.php?id=<?= $row['id'] ?>" class="btn btn-details">Details</a>
                        <button class="btn btn-cart add-to-cart" data-id="<?= $row['id'] ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script src="assets/js/cart.js"></script>
<?php include "includes/footer.php"; ?>
