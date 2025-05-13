<?php
session_start();

// Check if user is logged in and has admin role
if (empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'A') {
    header("Location: shop.php");
    exit;
}

// Connect to your database (replace DB_HOST, DB_USER, DB_PASSWORD, and DB_NAME with your actual database credentials)
$conn = new mysqli('localhost', 'root', '', 'book_store');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert new category into the database
    $category_name = $_POST["category_name"];
    $sql = "INSERT INTO categories (name) VALUES ('$category_name')";
    if ($conn->query($sql) === TRUE) {
        echo "New category added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

header("Location: Upload_book.php");

$conn->close();
?>
