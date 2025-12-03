<?php
session_start();
include 'config.php';

// Only allow super admin (or any admin) to access this page
if (!isset($_SESSION["id"]) || $_SESSION["usertype"] != "admin") {
    header("location: admin_login.php");
    exit;
}

// --- Handle Delete ---
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $sql_delete = "DELETE FROM admins WHERE id = $delete_id";
    if (mysqli_query($conn, $sql_delete)) {
        $success_message = "Admin deleted successfully.";
    } else {
        $error_message = "Error deleting admin: " . mysqli_error($conn);
    }
}

// --- Handle Add ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // plaintext
    $sql_insert = "INSERT INTO admins (username, password) VALUES ('$username', '$password')";
    if (mysqli_query($conn, $sql_insert)) {
        $success_message = "Admin added successfully.";
    } else {
        $error_message = "Error adding admin: " . mysqli_error($conn);
    }
}

// --- Handle Update ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_admin'])) {
    $id = (int)$_POST['id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // plaintext
    $sql_update = "UPDATE admins SET username='$username', password='$password' WHERE id=$id";
    if (mysqli_query($conn, $sql_update)) {
        $success_message = "Admin updated successfully.";
    } else {
        $error_message = "Error updating admin: " . mysqli_error($conn);
    }
}

// Fetch all admins
$sql_select = "SELECT * FROM admins";
$result = mysqli_query($conn, $sql_select);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Manage Admins</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/style.css">

</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="container mt-4">
        <h2>Manage Admins</h2>

        <?php if (isset($success_message)) : ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Add New Admin Form -->
        <div class="card mb-4 mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h4>Add New Admin</h4>
                <form method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="password" placeholder="Password (plaintext)" required>
                    </div>
                    <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
                </form>
            </div>
        </div>

        <!-- Admin List -->
        <h4>Existing Admins</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password (plaintext)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="text" class="form-control mb-1 mr-1" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                        </td>
                        <td>
                                <input type="text" class="form-control mb-1 mr-1" name="password" value="<?php echo htmlspecialchars($row['password']); ?>" required>
                        </td>
                        <td>
                                <button type="submit" name="update_admin" class="btn btn-sm btn-primary mb-1">Update</button>
                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this admin?')">Delete</a>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
