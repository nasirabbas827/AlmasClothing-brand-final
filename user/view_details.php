<?php
session_start();
include('config.php');

// Redirect to login if user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Check if product_id is provided
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    header("Location: shop.php");
    exit;
}

$product_id = (int)$_GET['product_id'];

// Fetch product details
$sql_product = "SELECT p.*, c.name AS category_name 
                FROM products p 
                JOIN categories c ON p.CategoryID = c.id 
                WHERE p.ProductID = $product_id 
                LIMIT 1";
$result_product = mysqli_query($conn, $sql_product);

if (mysqli_num_rows($result_product) === 0) {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    exit;
}

$product = mysqli_fetch_assoc($result_product);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['ProductName']); ?> | Almas Clothing Brand</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .product-img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="../admin/products_images/<?php echo htmlspecialchars($product['ImageURL']); ?>" alt="Product Image" class="product-img img-fluid rounded shadow-sm">
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <h2 class="text-info"><?php echo htmlspecialchars($product['ProductName']); ?></h2>
                <p class="mb-1"><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
                <p class="mb-1"><strong>Price:</strong> <span class="text-danger"><?php echo number_format($product['Price'], 2); ?> Pkr</span></p>
                <p class="mb-1"><strong>Stock Available:</strong> <?php echo $product['StockQuantity']; ?></p>
                <p class="mb-1"><strong>Size Guide:</strong> <?php echo htmlspecialchars($product['SizeGuide']); ?></p>
                <p class="mb-2"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($product['Description'])); ?></p>

                <!-- Add to Cart Form -->
                <form action="add_to_cart.php" method="post" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                    <input type="hidden" class="max-quantity" value="<?php echo $product['StockQuantity']; ?>">

                    <div class="form-row align-items-center mb-3">
                        <div class="col-4">
                            <input type="number" class="form-control quantity" name="quantity" value="1" min="1" max="<?php echo $product['StockQuantity']; ?>" required>
                        </div>
                        <div class="col-8">
                            <?php if ($product['StockQuantity'] > 0): ?>
                                <button type="submit" class="btn btn-success w-100"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.add-to-cart-form');
            if(form){
                form.addEventListener('submit', function(e){
                    const qty = parseInt(form.querySelector('.quantity').value);
                    const maxQty = parseInt(form.querySelector('.max-quantity').value);

                    if(isNaN(qty) || qty < 1 || qty > maxQty){
                        e.preventDefault();
                        alert(`Quantity must be between 1 and ${maxQty}.`);
                    }
                });
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
