<?php
require 'database.php';
$stmt = "SELECT * FROM `books` WHERE `Reserve` = 'Y'";

$result = $conn->query($stmt);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_ISBN'])){
    $ISBN = $_POST['book_ISBN'];

    $removeSQL = "UPDATE `books` SET `reserve` = 'N' where `ISBN` = ?";
    $stmt = $conn->prepare($removeSQL);
    $stmt->bind_param("s", $ISBN);

    if ($stmt->execute()) {
        // echo "Reservation removed successfully.";
        // header('Location: reserved_books.php'); // Redirect to the same page to reflect changes
        // exit();
    } else {
        // echo "Failed to remove the reservation.";
    }
    $stmt->close();
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
    <a href="index.php">Home</a>
    <!-- <a href="login.php">Log In</a> -->
</body>
</html>