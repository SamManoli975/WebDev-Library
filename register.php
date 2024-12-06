<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <div class="title">
            <h2>Register</h2>
        </div>


        <?php
        $error_message = "";
        ?>

        <form method="post" name="userform">
            <p>UserName:
                <input type="text" class="form-control" name="UserName" required>
                <small id="usernameError" class="error-message"></small>
            </p>
            <p>Password:
                <input type="password" class="form-control" name="password" required>
            </p>
            <p>Password Validation:
                <input type="password" class="form-control" name="passwordVal" required>
            </p>
            <p>FirstName:
                <input type="text" class="form-control" name="FirstName" required>
            </p>
            <p>Surname:
                <input type="text" class="form-control" name="Surname" required>
            </p>
            <p>AddressLine1:
                <input type="text" class="form-control" name="Address1" required>
            </p>
            <p>AddressLine2:
                <input type="text" class="form-control" name="Address2">
            </p>
            <p>City:
                <input type="text" class="form-control" name="City" required>
            </p>
            <p>Telephone:
                <input type="text" class="form-control" name="Telephone">
            </p>
            <p>Mobile:
                <input type="text" class="form-control" name="Mobile" required>
            </p>
            <p><input type="submit" class="btn btn-primary" value="Add New" /></p>
            <a class="alreadyaccount" href="login.php">Already got an account?</a>
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
<?php
//mobile numbers numeric
//mobile numbers 10 characters long
//passowrds should be 6 characters okay
//password confirmatino functino
//usernames are unique
require_once "database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Un = $_POST['UserName'];
    $p = $_POST['password'];
    $pVal = $_POST['passwordVal'];
    $FN = $_POST['FirstName'];
    $LN = $_POST['Surname'];
    $Address1 = $_POST['Address1'];
    $Address2 = $_POST['Address2'];
    $City = $_POST['City'];
    $Telephone = $_POST['Telephone'];
    $Mobile = $_POST['Mobile'];

    //check if mobile is numeric and 10 digits long
    if (!is_numeric($Mobile) || strlen($Mobile) != 10) {
        echo "<script>alert('Mobile number must be numeric and exactly 10 digits long');</script>";
        exit();
    }

    //validate password
    if (strlen($p) != 6) {
        echo "<script>alert('Password must be exactly 6 characters long');</script>";
        exit();
    }

    // check if password matches confirmation
    if ($p !== $pVal) {
        echo "<script>alert('Passwords do not match. Please try again');</script>";
        exit();
    }

    // check if username already exists in the database
    $sql_check = "SELECT * FROM users WHERE UserName = '$Un'";
    $result = $conn->query($sql_check);

    //if it returns result for the user searrch
    if ($result->num_rows > 0) {
        echo "<script>alert('Username already exists. Please choose another one');</script>";
        exit();
    }

    // Insert new user into the database
    $sql = "INSERT INTO users (UserName, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) 
        VALUES ('$Un', '$p', '$FN', '$LN', '$Address1', '$Address2', '$City', '$Telephone', '$Mobile')";

    if ($conn->query($sql) === TRUE) {
        $error_message = "New user created successfully";
    } else {
        $error_message = "Error:" . $conn->error;
    }

    $conn->close();
    header('location: login.php');
    exit();
}
?>