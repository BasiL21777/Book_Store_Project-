

<form method="post" >
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

// Get all categories from the database
$sql = "SELECT * FROM categories ORDER BY name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output each category as a list item
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<input type='radio' name='category'>" . $row["name"] ."<br>" ;
    }
    echo "</ul>";
} else {
    echo "No categories found";
}
$conn->close();
if (!empty($_POST)) {
header("Location:Upload_book.php");
}
?>
    <label for="category_name">New Category Name:</label>
    <input type="text" id="category_name" name="category_name" required>
    <button type="submit" id="sub">Add</button>

</form>

<style>


form{
    margin-left: 30%;
    margin-top: 5%;
    padding: 15px 15px;
    width:500px;
    background-color: rgba(227,230,243,255);
    border-radius: 10px;
    border: 1px solid black ;
}

label{
    font-size: 17px;
    font-weight: 400;
}
#sub{
    margin-top: 15px;
    margin-left: 42%;
    width: 70px;
    color: whitesmoke;
    background-color:cadetblue;
    border: none;
    font-size: 17px;
    padding: 5px 5px;
    border-radius: 15px;
    display: inline;
}
#box{
    display:flex;
}
#category_name{
 border: none;
 text-align: center;
    border-radius: 15px;
    padding: 5px ;
}
</style>
