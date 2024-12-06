<?php
//require the database.php to connect to the database
require 'database.php';


//if the server method is post and if the book_ISBN is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_ISBN'])){
    //set variables
    $ISBN = $_POST['book_ISBN'];
    //the sql query to remove a book from reservatins
    $removeSQL = "UPDATE `books` SET `reserve` = 'N' where `ISBN` = ?";
    $stmt = $conn->prepare($removeSQL);
    $stmt->bind_param("s", $ISBN);

    //execute
    if ($stmt->execute()) {
        $removeReservation = "DELETE FROM `reservations` WHERE `ISBN` = ?";
        $stmt1 = $conn->prepare($removeReservation);
        $stmt1->bind_param("s", $ISBN);
        $stmt1->execute();
        // echo "Reservation removed successfully.";
        // header('Location: reserved_books.php'); // Redirect to the same page to reflect changes
        // exit();
    } else {
        // echo "Failed to remove the reservation.";
    }
    $stmt->close();
    //statment to get all the books that are reserved
    $stmt = "SELECT * FROM `books` WHERE `Reserve` = 'Y'";

    $result = $conn->query($stmt);
}else{
    //get the reserved books again
    $stmt = "SELECT * FROM `books` WHERE `Reserve` = 'Y'";

    $result = $conn->query($stmt);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
</head>
<body>
<div class="links">


        <a class="linka" href="register.php">Sign up now</a>
        <a class="linka" href="login.php">Log In</a>
        <a class="linka" href="reservedbooks.php">ReservedBooks</a>
        <a class="linka" href="index.php">Home</a>



    </div>

    <h1 class="title1"> Reserved Books</h1>
    <?php if($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Edition</th>
                    <th>ISBN</th>
                    <th>Reservation</th>
                    
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlentities($row['BookTitle']); ?></td>
                        <td><?= htmlentities($row['Author']); ?></td>
                        <td><?= htmlentities($row['Year']); ?></td>
                        <td><?= htmlentities($row['Edition']); ?></td>
                        <td><?= htmlentities($row['ISBN']); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="book_ISBN" value="<?= htmlentities($row['ISBN']); ?>">
                                <button type="submit">Remove Reservation</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No reserved books found.</p>
    <?php endif; ?>
    <!-- <a href="login.php">Log In</a> -->
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