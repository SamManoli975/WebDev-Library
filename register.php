<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
    <h2 class="mt-4">Add A New User</h2>
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
    </form>
    <a href="login.php">Already got an account?</a>
    </div>
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

    if (!is_numeric($Mobile) || strlen($Mobile) != 10) {
        echo "<div class='alert alert-danger'>Mobile number must be numeric and exactly 10 digits long.</div>";
        exit();
    }

    // Validate password
    if (strlen($p) != 6) {
        echo "<div class='alert alert-danger'>Password must be exactly 6 characters long.</div>";
        exit();
    }

    // Check if password matches confirmation
    if ($p !== $pVal) {
        echo "<div class='alert alert-danger'>Passwords do not match. Please try again.</div>";
        exit();
    }

    // Check if username already exists in the database
    $sql_check = "SELECT * FROM users WHERE UserName = '$Un'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        echo "<div class='alert alert-danger'>Username already exists. Please choose another one.</div>";
        exit();
    }

    // Insert new user into the database
    $sql = "INSERT INTO users (UserName, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) 
        VALUES ('$Un', '$p', '$FN', '$LN', '$Address1', '$Address2', '$City', '$Telephone', '$Mobile')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New user created successfully.</div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header('location: login.php');
    exit();
}
?>