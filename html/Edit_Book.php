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

$error_Fields = array();
$image_errors = array();
$book = null;

// Fetch book data if ID is provided
if (isset($_GET['id'])) {
    $conn = mysqli_connect("localhost", "root", "", "book_store");
    if (!$conn) {
        echo mysqli_connect_error();
        exit;
    }

    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM `books` WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $book = $row;
    } else {
        echo "Book not found.";
        exit;
    }
    mysqli_close($conn);
} else {
    echo "No book ID provided.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    if (!(isset($_POST["title"]) && !empty($_POST["title"]))) {
        $error_Fields[] = "title";
    }
    if (!(isset($_POST["price"]) && !empty($_POST["price"]))) {
        $error_Fields[] = "price";
    }
    if (!(isset($_POST["author"]) && !empty($_POST["author"]))) {
        $error_Fields[] = "author";
    }
    if (!(isset($_POST["description"]) && !empty($_POST["description"]))) {
        $error_Fields[] = "description";
    }

    // Image handling
    $image_name = $_FILES['cover_image']['name'];
    $image_type = $_FILES['cover_image']['type'];
    $image_size = $_FILES['cover_image']['size'];
    $image_tmp_name = $_FILES['cover_image']['tmp_name'];
    $image_error = $_FILES['cover_image']['error'];

    if ($image_error == 4) {
        // No new image uploaded, retain existing image
        $image = $book['cover_image'];
    } else {
        // Validate image
        if ($image_size > 1000000) {
            $image_errors[] = "<div>File can't be more than 1MB</div>";
        }

        $allowed_extensions = array('jpg', 'gif', 'png', 'jpeg');
        $file_name_array = explode('.', $image_name);
        $file_extension = strtolower(end($file_name_array));

        if (!in_array($file_extension, $allowed_extensions)) {
            $image_errors[] = "<div>File not valid</div>";
        }
    }

    // If no errors, proceed with update
    if (empty($error_Fields) && empty($image_errors)) {
        $conn = mysqli_connect("localhost", "root", "", "book_store");
        if (!$conn) {
            echo mysqli_connect_error();
            exit;
        }

        // Prepare data
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $title = mysqli_real_escape_string($conn, $_POST["title"]);
        $price = mysqli_real_escape_string($conn, $_POST["price"]);
        $description = mysqli_real_escape_string($conn, $_POST["description"]);
        $author = mysqli_real_escape_string($conn, $_POST["author"]);
        $category = mysqli_real_escape_string($conn, $_POST["category"]);

        // Handle image upload if a new image is provided
        if ($image_error != 4) {
            $image = "/Projects/Book_store_Project/media/books/" . $title . "_" . $image_name;
            $image_path = $_SERVER['DOCUMENT_ROOT'] . "\\Projects\\Book_store_Project\\media\\books\\" . $title . "_" . $image_name;
            move_uploaded_file($image_tmp_name, $image_path);
        }

        // Update query
        $query = "UPDATE `books` SET
                  title = '$title',
                  author = '$author',
                  description = '$description',
                  price = '$price',
                  cover_image = '$image',
                  category_id = '$category'
                  WHERE id = '$id'";

        if (mysqli_query($conn, $query)) {
            header("Location: Manager_DashBoard.php");
            exit;
        } else {
            echo mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
?>

<html>
<head>
    <title>Edit Book</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="txt" value="<?= isset($_POST['title']) ? $_POST['title'] : $book['title'] ?>">
        <?php if (in_array('title', $error_Fields)) echo "*Please enter the title"; ?>
        <br>

        <label for="price">Price</label>
        <input type="number" name="price" id="price" min="0" value="<?= isset($_POST['price']) ? $_POST['price'] : $book['price'] ?>">
        <?php if (in_array('price', $error_Fields)) echo "*Please enter the price"; ?>
        <br>

        <label for="author">Author</label>
        <input type="text" name="author" id="author" value="<?= isset($_POST['author']) ? $_POST['author'] : $book['author'] ?>" class="txt">
        <?php if (in_array('author', $error_Fields)) echo "*Please enter the author"; ?>
        <br>

        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"><?= isset($_POST['description']) ? $_POST['description'] : $book['description'] ?></textarea>
        <?php if (in_array('description', $error_Fields)) echo "*Please enter the description"; ?>
        <br>

        <label for="category">Category</label>
        <div id="box">
            <div id="category">
                <?php
                $conn = mysqli_connect("localhost", "root", "", "book_store");
                $sql = "SELECT * FROM categories ORDER BY name";
                $result = mysqli_query($conn, $sql);

                if ($result && $result->num_rows > 0) {
                    echo "<ul>";
                    while ($category = $result->fetch_assoc()) {
                        $selected = ($category['id'] == $book['category_id']) ? "checked" : "";
                        echo "<input type='radio' name='category' value='" . $category['id'] . "' $selected>" . $category['name'] . "<br>";
                    }
                    echo "</ul>";
                } else {
                    echo "No categories found";
                }
                mysqli_close($conn);
                ?>
            </div>
            <a href="categories.php">Add Category</a>
        </div>
        <br>

        <label for="file">Cover Image:</label>
        <input type="file" name="cover_image" id="file">
        <br>
        <?php
        if (!empty($image_errors)) {
            foreach ($image_errors as $error) {
                echo $error;
            }
        }
        ?>

        <input type="submit" name="update" value="Update" id="sub">
    </form>
</body>
</html>

<style>
#category {
    width: 200px;
    height: 150px;
    overflow: scroll;
    margin-left: 70px;
    border: 1px solid gray;
    border-radius: 5px;
    background-color: aliceblue;
}
.txt {
    border-radius: 5px;
    border: none;
    height: 25px;
    width: 200px;
    text-align: center;
}
textarea {
    width: 300px;
    height: 70px;
    border-radius: 5px;
    border: 1px solid gray;
}
form {
    margin-left: 30%;
    margin-top: 5%;
    padding: 15px 15px;
    width: 500px;
    background-color: rgba(227,230,243,255);
    border-radius: 10px;
    border: 1px solid black;
}
#price {
    width: 60px;
}
label {
    font-size: 17px;
    font-weight: 400;
}
#sub {
    margin-top: 15px;
    margin-left: 42%;
    width: 70px;
    color: whitesmoke;
    background-color: cadetblue;
    border: none;
    font-size: 17px;
    padding: 5px 5px;
    border-radius: 5px;
}
#box {
    display: flex;
}
a {
    margin-top: 120px;
    margin-left: 15px;
    color: whitesmoke;
    background-color: cadetblue;
    border: none;
    font-size: 15px;
    padding: 5px;
    border-radius: 5px;
    height: 25px;
    text-decoration: none;
}
</style>
