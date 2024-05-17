<?php
// check the method which user used is post (click submit)
$error_Fields = array();

// session_start();
// echo var_dump($_SESSION);

echo(var_dump($_POST));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    #validation 
    // if the name is empty 
    if (!(isset($_POST["title"]) && !empty($_POST["title"]))) {
        $error_Fields[] = "title"; # append 
    }
    if (!(isset($_POST["price"]) && !empty($_POST["price"]))) {
        $error_Fields[] = "price"; # append 
    }
    if (!(isset($_POST["author"]) && !empty($_POST["author"]))) {
        $error_Fields[] = "author"; # append 
    }
    if (!(isset($_POST["description"]) && !empty($_POST["description"]))) {
        $error_Fields[] = "description"; # append 
    }




    $image_name = $_FILES['cover_image']['name'];
    $image_type = $_FILES['cover_image']['type'];
    $image_Size = $_FILES['cover_image']['size'];
    $image_tmp_name = $_FILES['cover_image']['tmp_name'];
    $image_error = $_FILES['cover_image']['error'];

    // store errors in error arryay

    $image_errors = array();


    // if he didnt upload any files
// there is alot of errors in php by default in $_FILES['avatar']['error']:

    if ($image_error == 4) {
        $image_errors[] = "<div>u should upload file</div>";
    } else {
        //check size
        if ($image_Size > 1000000) {
            #append the error
            $image_errors[] = "<div>File's cant be more than 5000</div>";
        }

        //check type
        $allowed_extentions = array('jpg', 'gif', 'png', 'jpeg');
        // explode : split string to array , end: get the last item
        $file_name_array = explode('.', $image_name);
        $file_Extention = strtolower(end($file_name_array));

        // he uploaded and the extition is wrong
        if (!in_array($file_Extention, $allowed_extentions)) {
            $image_errors[] = "<div>File not valid</div>";
        }

    }

    // there is no errors
    if (!$error_Fields) {
        // create connection
        $conn = mysqli_connect("Localhost", "root", "", "Book_Store");

        // prepare data 
        // to escape special char to avoid sql injection
        $title = mysqli_escape_string($conn, $_POST["title"]);
        $price = mysqli_escape_string($conn, $_POST["price"]);
        $description = mysqli_escape_string($conn, $_POST["description"]);
        $Author = mysqli_escape_string($conn, $_POST["author"]);
        $category = $_POST["category"];








        if (empty($image_errors)) {
            $image = "/Projects/Book_store_Project/media/books/" . $title . "_" . $image_name;

            // insertion query
            $query = "INSERT INTO `books` (title, author, description, price, cover_image,category_id) VALUES ('$title', '$Author', '$description', '$price', '$image','$category')";

            $image = "/Projects/Book_store_Project/media/books/" . $title . "_" . $image_name;
            // if the query executed
            if (mysqli_query($conn, $query)) {
                $image = $_SERVER['DOCUMENT_ROOT'] . "\\Projects\\Book_store_Project\\media\\books\\" . $title . "_" . $image_name;
                move_uploaded_file($image_tmp_name, $image);
                header("Location:Manager_DashBoard.php"); #go to list page
                exit;
            } else {
                echo mysqli_error($conn);
            }

            $image = "/Projects/Book_store_Project/media/books/" . $title . "_" . $image_name;

        }





        mysqli_close($conn);
    }
}

?>


<html>

<body>
    <form method="post" enctype="multipart/form-data">


        <label for="title"> Title </label>
        <input type="text" name="title" id="title" class="txt" value="<?= (!empty($_POST["title"]) )? $_POST["title"] : "" ?>">
        <?php if (in_array('title', $error_Fields))
            echo "*Please enter the title"; ?>
        <br>


        <label for="price"> Price </label>
        <input type="number" name="price" id="price" min="0"
            value="<?= !empty($_POST["price"]) ? $_POST["price"] : "" ?>">
        <?php if (in_array('price', $error_Fields))
            echo "*Please enter the price"; ?>
        <br>


        <label for="auther"> Author </label>
        <input type="text" name="author" id="auther" value="<?= isset($_POST["Email"]) ? $_POST["Email"] : "" ?>" class="txt">
        <?php if (in_array('author', $error_Fields))
            echo "*Please enter the Author"; ?>
        <br>



        <label for="description"> description </label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
        <?php if (in_array('description', $error_Fields))
            echo "*Please enter the description"; ?>
        <br>


        <label for="category"> category </label>
        <div id="box">
        <div id="category">
            <?php
        $conn = mysqli_connect("Localhost", "root", "", "Book_Store");

            // Get all categories from the database
            $sql = "SELECT * FROM categories ORDER BY name";
            $result = mysqli_query($conn,$sql);

            if ($result->num_rows > 0) {
                // Output each category as a list item
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
              
                    echo "<input type='radio' name='category' value='" . $row['id'] . "'>" . $row["name"] . "<br>";
                }
                echo "</ul>";
            } else {
                echo "No categories found";
            }

            ?>
        </div>
        <a href="categories.php">Add Category</a>
        </div>
        <br>


        <label for="file">cover_image:</label>
        <input type="file" name="cover_image" id="file">
        <br>
        <?php
        if (!empty($image_errors)) {
            foreach ($image_errors as $error) {
                echo $error;
            }
        }
        ?>


        <input type="submit" name="add" value="Submit" id="sub">
    </form>


</body>

</html>

<style>

#category{
    width: 200px;
height: 150px;
overflow: scroll;
margin-left: 70px;
border: 1px solid gray;
border-radius: 5px;
background-color:aliceblue;
}
.txt{
    border-radius: 5px;
    border: none;
    height: 25px;
    width: 200px;
    text-align: center;
}
textarea{
    width: 300px;
    height: 70px;
border-radius: 5px;
border: 1px solid gray;

}

form{
    margin-left: 30%;
    margin-top: 5%;
    padding: 15px 15px;
    width:500px;
    background-color: rgba(227,230,243,255);
    border-radius: 10px;
    border: 1px solid black ;
}

#price{
    width: 60px;
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
    border-radius: 5px;
}
#box{
    display:flex;
}
a{
    margin-top: 120px;
    margin-left: 15px;
    color: whitesmoke;
    background-color:cadetblue;
    border: none;
    font-size: 15px;
    padding: 5px;
    border-radius: 5px;
    height: 25px;
    text-decoration: none;
}
</style>