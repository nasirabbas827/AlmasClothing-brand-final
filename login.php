<?php 
include('config.php');

$email = $password = "";
$email_err = $password_err = "";
$login_err = "";

// check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // if no errors, check credentials
    if (empty($email_err) && empty($password_err)) {

        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {

            mysqli_stmt_bind_result($stmt, $id, $email_db, $hashed_password);

            if (mysqli_stmt_fetch($stmt)) {

                if (password_verify($password, $hashed_password)) {

                    session_start();
                    $_SESSION["id"] = $id;
                    $_SESSION["email"] = $email_db;

                    header("location: user/user_home.php");
                    exit;

                } else {
                    $login_err = "Incorrect password.";
                }
            }

        } else {
            $login_err = "No account found with that email.";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">

            <h2 class="text-center mt-4">User Login</h2>
            <p class="text-center">Please fill in your credentials to log in.</p>

            <!-- SHOW ERROR MESSAGE INSIDE FORM -->
            <?php if (!empty($login_err)) { ?>
                <div class="alert alert-danger text-center">
                    <?php echo $login_err; ?>
                </div>
            <?php } ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" 
                           name="email" 
                           class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo htmlspecialchars($email); ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" 
                           name="password" 
                           class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>

                <div class="form-group text-center">
                    <input type="submit" value="Log in" class="btn btn-primary">
                </div>

            </form>

            <p class="text-center">
                Don't have an account? <a href="register.php">Register here</a>
            </p>

            <p class="text-center">
                Go to <a href="index.php">Home Page</a>
            </p>

        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
