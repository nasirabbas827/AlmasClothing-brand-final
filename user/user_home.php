<?php
session_start();
include('config.php');  

// Redirect to login if user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// --- Fetch Categories ---
$sql_categories = "SELECT id, name FROM categories ORDER BY name ASC";
$result_categories = mysqli_query($conn, $sql_categories);

// --- Base Product Query ---
$sql_products = "SELECT p.*, c.name AS category_name 
                 FROM products p 
                 JOIN categories c ON p.CategoryID = c.id";

// --- Filter/Search Parameters ---
$search_query = $_GET['search'] ?? '';
$category_id = $_GET['category'] ?? 'all';
$product_name_filter = $_GET['product_name'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

$where_conditions = [];

// Filter by search query
if (!empty($search_query)) {
    $safe_search = mysqli_real_escape_string($conn, $search_query);
    $where_conditions[] = "p.ProductName LIKE '%$safe_search%'";
}

// Filter by category
if ($category_id !== 'all') {
    $safe_category = (int)$category_id;
    $where_conditions[] = "p.CategoryID = $safe_category";
}

// Filter by specific product name
if (!empty($product_name_filter)) {
    $safe_product = mysqli_real_escape_string($conn, $product_name_filter);
    $where_conditions[] = "p.ProductName LIKE '%$safe_product%'";
}

// Filter by price range
if (!empty($min_price) && !empty($max_price)) {
    $where_conditions[] = "p.Price BETWEEN " . (float)$min_price . " AND " . (float)$max_price;
} elseif (!empty($min_price)) {
    $where_conditions[] = "p.Price >= " . (float)$min_price;
} elseif (!empty($max_price)) {
    $where_conditions[] = "p.Price <= " . (float)$max_price;
}

// Apply WHERE conditions if any
if (!empty($where_conditions)) {
    $sql_products .= " WHERE " . implode(' AND ', $where_conditions);
}

// Order products by name
$sql_products .= " ORDER BY p.ProductName ASC";

$result_products = mysqli_query($conn, $sql_products);

// --- New Arrivals Carousel ---
$sql_new_arrivals = "SELECT ProductID, ProductName, Price, ImageURL 
                     FROM products ORDER BY Timestamp DESC LIMIT 6";
$result_new_arrivals = mysqli_query($conn, $sql_new_arrivals);
$products_count = mysqli_num_rows($result_new_arrivals);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Almas Clothing Brand | Shop Our Collection</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .product-card-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <!-- Header -->
    <header class="jumbotron jumbotron-fluid text-center bg-light">
        <div class="container">
            <h1 class="display-4">Welcome to Almas Clothing Brand</h1>
            <p class="lead">Discover the latest in Eastern Formal, Party, and Wedding Wear.</p>
        </div>
    </header>

    <!-- New Arrivals -->
    <div class="container mt-5">
        <h2 class="mb-4">✨ New Arrivals</h2>
        <div id="new-arrivals-carousel" class="carousel slide" data-ride="carousel" data-interval="5000">
            <div class="carousel-inner">
                <?php
                $active = true;
                for ($i = 0; $i < $products_count; $i += 3) {
                    echo '<div class="carousel-item ' . ($active ? 'active' : '') . '"><div class="row">';
                    for ($j = $i; $j < min($i + 3, $products_count); $j++) {
                        mysqli_data_seek($result_new_arrivals, $j);
                        $row = mysqli_fetch_assoc($result_new_arrivals);
                        ?>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <img src="../admin/products_images/<?php echo htmlspecialchars($row['ImageURL']); ?>" class="card-img-top product-card-img" alt="Product Image">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['ProductName']); ?></h5>
                                    <p class="card-text mb-2 text-danger font-weight-bold"><?php echo number_format($row['Price'],2); ?> Pkr</p>
                                    <a href="#available-products" class="btn btn-sm btn-outline-info mt-auto">View & Buy</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    echo '</div></div>';
                    $active = false;
                }
                ?>
            </div>
            <a class="carousel-control-prev" href="#new-arrivals-carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#new-arrivals-carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
        <?php if ($products_count == 0): ?>
            <div class="alert alert-warning mt-3">No new arrivals yet. Check back soon!</div>
        <?php endif; ?>
    </div>

    <hr>

    <!-- Full Collection -->
    <div class="container mt-5" id="available-products">
        <h2 class="mb-4">🛍️ Shop Our Full Collection</h2>

        <!-- Filters Form -->
        <form method="GET" class="mb-4 p-3 border rounded bg-light">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label>Quick Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Product Name" value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Category</label>
                    <select name="category" class="custom-select">
                        <option value="all" <?php if($category_id=='all') echo 'selected'; ?>>All Categories</option>
                        <?php 
                        mysqli_data_seek($result_categories, 0);
                        while($row = mysqli_fetch_assoc($result_categories)) : ?>
                            <option value="<?php echo $row['id']; ?>" <?php if($row['id']==$category_id) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label>Min Price (Pkr)</label>
                    <input type="number" name="min_price" class="form-control" value="<?php echo htmlspecialchars($min_price); ?>" min="0">
                </div>
                <div class="col-md-2 mb-3">
                    <label>Max Price (Pkr)</label>
                    <input type="number" name="max_price" class="form-control" value="<?php echo htmlspecialchars($max_price); ?>" min="0">
                </div>
                <div class="col-md-2 mb-3">
                    <button class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Apply Filters</button>
                </div>
            </div>
        </form>

        <!-- Product Grid -->
        <?php if(mysqli_num_rows($result_products) > 0): ?>
            <div class="row">
                <?php while($row = mysqli_fetch_assoc($result_products)): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="../admin/products_images/<?php echo htmlspecialchars($row['ImageURL']); ?>" class="card-img-top product-card-img" alt="Product Image">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-info"><?php echo htmlspecialchars($row['ProductName']); ?></h5>
                                <p class="card-text small mb-1">Category: <?php echo htmlspecialchars($row['category_name']); ?></p>
                                <p class="card-text small mb-1">Description: <?php echo substr(htmlspecialchars($row['Description']),0,70) . (strlen($row['Description'])>70?'...':''); ?></p>
                                <h6 class="text-danger my-2"><?php echo number_format($row['Price'],2); ?> Pkr</h6>
                                <p class="card-text small mb-2 text-muted">Stock: <?php echo $row['StockQuantity']; ?></p>

                                <form action="add_to_cart.php" method="post" class="add-to-cart-form mt-auto">
                                    <input type="hidden" name="product_id" value="<?php echo $row['ProductID']; ?>">
                                    <input type="hidden" class="max-quantity" value="<?php echo $row['StockQuantity']; ?>">
                                    <div class="form-row align-items-center">
                                        <div class="col-5">
                                            <input type="number" class="form-control quantity" name="quantity" value="1" min="1" max="<?php echo $row['StockQuantity']; ?>" required>
                                        </div>
                                        <div class="col-7">
                                            <?php if($row['StockQuantity']>0): ?>
                                                <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                                            <?php else: ?>
                                                <button class="btn btn-secondary btn-sm w-100" disabled>Out of Stock</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a href="view_details.php?product_id=<?php echo $row['ProductID']; ?>" class="btn btn-outline-info btn-sm mt-2 w-100">View Details</a>
                                    <a href="view_ratings_reviews.php?product_id=<?php echo $row['ProductID']; ?>" class="btn btn-outline-secondary btn-sm mt-2 w-100">Reviews</a>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-3">No products found. Try adjusting your filters.</div>
        <?php endif; ?>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Only validate add-to-cart forms
            const cartForms = document.querySelectorAll('.add-to-cart-form');
            cartForms.forEach(form => {
                form.addEventListener('submit', function(e){
                    const quantityInput = form.querySelector('.quantity');
                    const maxQtyInput = form.querySelector('.max-quantity');
                    if (!quantityInput || !maxQtyInput) return;

                    const qty = parseInt(quantityInput.value);
                    const maxQty = parseInt(maxQtyInput.value);

                    if (isNaN(qty) || qty < 1 || qty > maxQty) {
                        e.preventDefault();
                        alert(`Quantity must be between 1 and ${maxQty}.`);
                    }
                });
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
