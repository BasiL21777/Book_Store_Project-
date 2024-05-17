<?php 
session_start();
echo var_dump($_SESSION)."<br>";
echo var_dump($_GET);

// Create connection
$conn = mysqli_connect("localhost", "root", "", "book_store");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$usr_id=$_SESSION['id'];
$book_id=$_GET['id'];
$quantity=$_GET['quantity'];


$query = "INSERT INTO `Cart` (`user_id`, book_id, quantity) VALUES ('$usr_id', '$book_id', '$quantity')";
if (mysqli_query($conn, $query)) {
        header("Location:shop.php");
        exit;
} else {
    $error = mysqli_error($conn);
}
mysqli_close($conn);


?>