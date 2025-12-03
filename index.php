<?php
// Start session for checking login status (public page, no login required)
session_start();
include('config.php'); // Database connection

// Check if user is logged in
$is_logged_in = isset($_SESSION['id']);

// --- Retrieve categories for filter ---
$sql_categories = "SELECT id, name FROM categories ORDER BY name ASC";
$result_categories = mysqli_query($conn, $sql_categories);

// --- Retrieve products with category info ---
$sql_products = "SELECT p.*, c.name AS category_name FROM products p
                 JOIN categories c ON p.CategoryID = c.id";

// --- Initialize and sanitize filters ---
$search_query = $_GET['search'] ?? '';
$category_id = $_GET['category'] ?? 'all';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

$where_conditions = [];

// Filter by search
if (!empty($search_query)) {
    $safe_search = mysqli_real_escape_string($conn, $search_query);
    $where_conditions[] = "p.ProductName LIKE '%$safe_search%'";
}

// Filter by category
if ($category_id !== 'all') {
    $safe_category = (int)$category_id;
    $where_conditions[] = "p.CategoryID = $safe_category";
}

// Filter by price range
if (!empty($min_price) && !empty($max_price)) {
    $safe_min = (float)$min_price;
    $safe_max = (float)$max_price;
    $where_conditions[] = "p.Price BETWEEN $safe_min AND $safe_max";
}

// Add WHERE clause if filters applied
if (!empty($where_conditions)) {
    $sql_products .= ' WHERE ' . implode(' AND ', $where_conditions);
}

$sql_products .= ' ORDER BY p.ProductID DESC';
$result_products = mysqli_query($conn, $sql_products);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almas Clothing Brand | Eastern Formal & Wedding Wear</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .jumbotron {
            height: 550px;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('./images/hotel.jpg') center/cover no-repeat;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .jumbotron h1 {
            font-size: 3rem;
            text-shadow: 2px 2px 4px #000;
        }
        .jumbotron p {
            font-size: 1.5rem;
        }
        .product-card-img {
            object-fit: cover;
            width: 100%;
            height: 250px;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Hero Section -->
<div class="jumbotron">
    <h1 class="text-white">Experience Almas Clothing Brand</h1>
    <p>Exclusive Eastern Formal, Party, and Wedding Wear.</p>
    <?php if (!$is_logged_in): ?>
        <a href="login.php" class="btn btn-primary btn-lg mx-2"><i class="fas fa-sign-in-alt"></i> Login</a>
    <?php else: ?>
        <a href="user/user_home.php" class="btn btn-success btn-lg"><i class="fas fa-shopping-bag"></i> Continue Shopping</a>
    <?php endif; ?>
</div>

<!-- New Arrivals Carousel -->
<div class="container mt-5">
    <h2 class="text-center mb-4">New Arrivals</h2>
    <div id="new-arrivals-carousel" class="carousel slide" data-ride="carousel" data-interval="5000">
        <div class="carousel-inner">
            <?php
            $sql_new = "SELECT * FROM products ORDER BY Timestamp DESC LIMIT 6";
            $res_new = mysqli_query($conn, $sql_new);
            $count_new = mysqli_num_rows($res_new);
            $active = true;

            for ($i = 0; $i < $count_new; $i += 3) {
                echo '<div class="carousel-item ' . ($active ? 'active' : '') . '"><div class="row">';
                for ($j = $i; $j < min($i+3, $count_new); $j++) {
                    mysqli_data_seek($res_new, $j);
                    $row = mysqli_fetch_assoc($res_new);
                    ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <img class="card-img-top product-card-img" src="admin/products_images/<?php echo htmlspecialchars($row['ImageURL']); ?>" alt="Product Image">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['ProductName']); ?></h5>
                                <p class="card-text text-danger font-weight-bold"><?php echo number_format($row['Price'], 2); ?> Pkr</p>
                                <a href="login.php" class="mt-auto btn btn-info btn-sm">View & Login to Buy</a>
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
</div>

<!-- Full Collection & Filters -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Full Collection</h2>

    <form method="GET" class="mb-4 p-3 border rounded bg-light">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Search Product</label>
                <input type="text" name="search" class="form-control" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by name">
            </div>
            <div class="col-md-3 mb-3">
                <label>Category</label>
                <?php mysqli_data_seek($result_categories, 0); ?>
                <select name="category" class="custom-select">
                    <option value="all" <?php if ($category_id=='all') echo 'selected'; ?>>All Categories</option>
                    <?php while ($row = mysqli_fetch_assoc($result_categories)) : ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id']==$category_id) echo 'selected'; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label>Min Price</label>
                <input type="number" name="min_price" class="form-control" value="<?php echo htmlspecialchars($min_price); ?>" placeholder="Min Pkr">
            </div>
            <div class="col-md-2 mb-3">
                <label>Max Price</label>
                <input type="number" name="max_price" class="form-control" value="<?php echo htmlspecialchars($max_price); ?>" placeholder="Max Pkr">
            </div>
            <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Filter</button>
            </div>
        </div>
    </form>

    <?php if(mysqli_num_rows($result_products) > 0): ?>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result_products)): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img class="card-img-top product-card-img" src="admin/products_images/<?php echo htmlspecialchars($row['ImageURL']); ?>" alt="Product Image">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['ProductName']); ?></h5>
                            <p class="card-text small mb-1">Category: <?php echo htmlspecialchars($row['category_name']); ?></p>
                            <p class="card-text small mb-1">Description: <?php echo substr(htmlspecialchars($row['Description']),0,70) . (strlen($row['Description'])>70?'...':''); ?></p>
                            <h6 class="text-danger my-2">Price: <?php echo number_format($row['Price'],2); ?> Pkr</h6>
                            <a href="login.php" class="btn btn-success btn-block mt-auto"><i class="fas fa-lock"></i> Login to Buy</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No products found for the selected criteria.</div>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="footer bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>Contact Almas</h5>
                <p>Email: info@almasbrand.com</p>
                <p>Phone: +92-XXX-XXXXXXX</p>
                <p>Address: Fashion Hub, City, Pakistan</p>
            </div>
            <div class="col-md-6">
                <h5>Follow Us</h5>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="#" class="text-white"><i class="fab fa-facebook-square"></i> Facebook</a></li>
                    <li class="list-inline-item"><a href="#" class="text-white"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li class="list-inline-item"><a href="#" class="text-white"><i class="fab fa-instagram"></i> Instagram</a></li>
                    <li class="list-inline-item"><a href="login.php" class="text-white"><i class="fas fa-file-alt"></i> Register Complaint</a></li>
                </ul>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-center">
                &copy; <?php echo date('Y'); ?> Almas Clothing Brand. All rights reserved.
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close connection
if(isset($conn)) { $conn->close(); }
?>
