<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // header("location: index.php");
    // exit;
}

require_once "database.php";

//set variables
$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //take the entered username and password
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    //if either is empty 
    if (empty($username)) {
        $username_err = "Please enter username.";
    }
    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    //if both are given
    if (empty($username_err) && empty($password_err)) {
        //query
        $sql = "SELECT UserName, Password FROM users WHERE UserName = ?";
        // echo "sql";
        //prepare
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            // echo "prepared";
            //execute
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                // echo "executed";
                //if result is one
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    //bind
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    // echo "binded";
                    //fetch
                    if (mysqli_stmt_fetch($stmt)) {
                        // echo "fetched";
                        // echo $password. $hashed_password;
                        //password == hashed_password(one fetched)
                        if ($password == $hashed_password) {
                            //logged in
                            // echo "verfied";

                            session_start();
                            //set session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;
                            //redirect
                            header("location: index.php");
                        } else {
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    //error message
                    $login_err = "Invalid username or password.";
                }
            } else {
                //err msg
                echo "Oops! Something went wrong. Please try again later.";
            }
            //close the sql
            mysqli_stmt_close($stmt);
        }
    }
    //close the connectino
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
</head>

<body>
    <div class="container">
        <div class="title">
            <h2>Login</h2>

        </div>


        <?php if (!empty($login_err)): ?>
            <div class="alert alert-danger"><?php echo $login_err; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username"
                    class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                    class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
    <footer class="cool-footer">
        <div class="footer-content">
            <div class="footer-section footer-links">
                <a href="#">Home</a>
                <a href="#">Reserved Books</a>
                <a href="#">Contact</a>
            </div>
        </div>
        <div class="footer-copyright">
            © 2024 Library Management System. All Rights Reserved.
        </div>
    </footer>
</body>

</html>