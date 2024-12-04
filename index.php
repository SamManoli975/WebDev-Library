<?php
// session_start();
// echo 'Session ID: ' . session_id();
//require my databse.php file
require_once 'database.php';
//my categoryQuery to select the relvant category information
$catQuery = "SELECT CategoryID, CategoryDetails FROM categories";
$catResult = $conn->query($catQuery);

//initalise empty searchresults array
$searchResults = [];
//if the server request method is post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //collect the post variables
    $title = $_POST['title'];
    $author = $_POST['author'];
    $cat = $_POST['category'];
    $ISBN = null;

    //query to collect my books
    $booksearch = "SELECT books.ISBN, books.BookTitle, books.Author, categories.CategoryDetails, books.Reserve
              FROM books 
              LEFT JOIN categories  ON books.Category = categories.CategoryID
              WHERE 1=1";

    //adding the title, author and category to the query
    $title ? $booksearch .= " AND books.BookTitle LIKE '%$title%'" : null;
    $author ? $booksearch .= " AND books.Author LIKE '%$author%'" : null;
    $cat ? $booksearch .= " AND books.Category = '$cat'" : null;
    $ISBN ? $booksearch .= " AND books.ISBN = '$ISBN'" : null;

    //result by passing the query to the connection
    $result = $conn->query($booksearch);
    //if the result is positive and has more than 0 rows
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            //add the row to search results
            $searchResults[] = $row;
        }
    }
}
//end of php
?>
<!-- start of html -->
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
    <title>Library</title>

</head>

<body>
    <main>

<div class="links">


<a class="linka" href="register.php">Sign up now</a>
<a class="linka" href="login.php">Log In</a>
<a class="linka" href="reservedbooks.php">ReservedBooks</a>
<a class="linka" href="index.php">Home</a>



</div>
    <h1>Search for a Book Within the Library</h1>
    <form method="POST">
        <label for="title">Book Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter book title">

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" placeholder="Enter author name">

        <!-- code for creating my options for categories by collecting them from my $catresult -->
        <label for="category">Category:</label>
        <select id="category" name="category">
            <option value="">Select a Category</option>
            <?php
            if ($catResult && $catResult->num_rows > 0) {
                while ($row = $catResult->fetch_assoc()) {
                    echo '<option value="' . $row['CategoryID'] . '">' . htmlentities($row['CategoryDetails']) . '</option>';
                }
            }
            ?>
        </select>
        <button type="submit"> Search </button>
    </form>
    <!-- if the search results are not  empty -->
    <?php if (!empty($searchResults)): ?>
        <h2>Search Results</h2>
        <div class="results">
            <!-- search for each book in the results -->
            <?php foreach ($searchResults as $book): ?>
                <ul>    
                    <li>
                        <strong>Title:</strong> <?= htmlentities($book['BookTitle']); ?><br>
                        <strong>Author:</strong> <?= htmlentities($book['Author']); ?><br>
                        <strong>Category:</strong> <?= htmlentities($book['CategoryDetails']); ?>
                        <strong>ISBN:</strong> <?= htmlentities($book['ISBN']); ?>
                        <?php if (!isset($book['Reserve']) || $book['Reserve'] === 'N'): ?>
                            <form action="reserve.php" method="POST">
                                <input type="hidden" name="book_ISBN" value="<?= htmlentities($book['ISBN']); ?>">
                                <button type="submit">Reserve?</button>
                            </form>
                        <?php else: ?>
                            <p><strong>Status:</strong> Already Reserved</p>
                        <?php endif; ?>
                    </li>
                </ul>
            <?php endforeach; ?>
        </div>
        <!-- if there were no search results then no results found -->
    <?php else: ?>
        <div class="results">
            <h2>No Results Found</h2>
        </div>
    <?php endif; ?>
    </main>
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