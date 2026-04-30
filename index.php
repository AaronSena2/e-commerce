<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>
<div class="container">
    <div class="jumbotron text-center mt-4">
        <h1 class="display-4">Welcome to Mercury Computer's Online Shop</h1>
        <p class="lead">Your one-stop solution for Laptops, Accessories, Networking, and more!</p>
        <a href="shop.php" class="btn btn-primary btn-lg">Shop Now</a>
    </div>
    <hr>
    <div class="row">
        <!-- Feature example cards -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="assets/images/sample1.jpg" class="card-img-top" alt="Featured Product">
                <div class="card-body">
                    <h5 class="card-title">Product Name</h5>
                    <p class="card-text">A brief description of the featured product.</p>
                    <a href="products/product_details.php?id=1" class="btn btn-outline-primary">Buy Now</a>
                </div>
            </div>
        </div>
        <!-- You can add more featured products here... -->
    </div>
</div>
<?php include "includes/footer.php"; ?>
