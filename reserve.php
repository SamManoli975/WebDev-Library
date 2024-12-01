<?php

    // session_start();

    // echo 'Session ID: ' . session_id();

    require 'database.php';
    
    // echo $_SESSION['book_ISBN'];
    // echo $_SESSION['book_ISBN'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_ISBN'])) {
        // $ISBN = $_SESSION['book_ISBN']; // Sanitize input
        $ISBN = $_POST['book_ISBN'];
        // echo $ISBN;
        // $_SESSION['book_ISBN'] = $bookISBN;
        // Example SQL query to reserve the book
        $sql = "UPDATE books SET reserve = 'Y' WHERE ISBN = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ISBN);
        if ($stmt->execute()){
            // echo "Book reserved successfully.";
        } else {
            echo "Failed to reserve the book.";
        }
        $stmt->close();
        header('Location: index.php');
        exit();
    } else {
        echo "Invalid request.";
    }
    
?>