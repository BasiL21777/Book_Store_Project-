<?php

session_start();

$conn = mysqli_connect("Localhost", "root", "", "Book_store");
if (!$conn) {
    echo mysqli_connect_error();
    exit;
}


if (empty($_GET["id"])) {

    if (isset($_POST["searchbox"])) {
        $search = mysqli_escape_string($conn, $_POST["searchbox"]);
        $qeury = "SELECT * FROM `books` WHERE (title LIKE '%" . $search . "%' OR author LIKE '%" . $search . "%')";
    } else {
        #Select all users
        // $qeury="select * from `user` ";
        $qeury = "select * from `books` ";

    }
} else {
    if (isset($_POST["searchbox"])) {
        $search = mysqli_escape_string($conn, $_POST["searchbox"]);
        $qeury = "SELECT b.id, b.title, b.author, b.description, b.price, b.category_id, b.cover_image, b.created_at, b.updated_at, c.name as category_name
        FROM books b
        JOIN categories c ON b.category_id = c.id
        WHERE( b.title LIKE '%$search%'
        OR b.author LIKE '%$search%'
        OR c.name LIKE '%$search%')
        AND b.category_id = " . $_GET['id'];
    } else {
        #Select all users
        // $qeury="select * from `user` ";
        $qeury = "SELECT b.id, b.title, b.author, b.description, b.price, b.category_id, b.cover_image, b.created_at, b.updated_at, c.name as category_name
        FROM books b
        JOIN categories c ON b.category_id = c.id
        WHERE b.category_id = " . $_GET['id'];

    }
}

$result_book = mysqli_query($conn, $qeury);


?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="describtion" content="This is the best book store at ever">
    <link rel="stylesheet" href="../media/icons/fontawesome-free-6.5.2-web/css/all.css">
    <link rel="stylesheet" href="../style/shop2.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
</head>

<body>
    <header>
        <a href="shop.php"> <img src="../media/icons/Screenshot 2024-04-26 142359.png" alt="logo" class="logo"></a>

        <div>
            <ul id="NavBar">
                <li><a href="shop.php" class="active">Shop</a></li>
                <li><a href="mailto:basel.yaser205@gamil.com">Contact</a></li>
                <?php
                if (!empty($_SESSION)) {
                    echo "<li><a href=\"logout.php\">Log out</a></li>";
                if($_SESSION['role']=="C"){
                    echo "
                    <li><a href=\"cart.php\" style=\"text-decoration: none;\"><i class=\"fa-solid fa-cart-shopping\"></i></a></li>";
                }
                } else {

                    echo "<li><a href=\"signUp.php\">New Account</a></li>";

                }
                ?>

            </ul>
            <script src="https://kit.fontawesome.com/1f95460999.js" crossorigin="anonymous"></script>
        </div>
    </header>

    <section id="announce">
        <h1> # Best_Book_Store</h1>
        <form method="post" id="search">
            <input type="search" placeholder="Search" name="searchbox" id="searchbox"
                value="<?= !empty($_POST["searchbox"]) ? $_POST["searchbox"] : "" ?>">
            <!-- <i class="fa-solid fa-magnifying-glass"></i> -->
            <input type="submit" name="search" id="button" value="Search">
        </form>
        <div>
            <ul id="Category">
                <?php
                $sql = "SELECT * FROM categories ORDER BY name";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output each category as a list item
                    while ($row = $result->fetch_assoc()) {
                        if (!empty($_GET) && $_GET['id'] == $row['id']) {
                            echo "<li id='active_cate'>";
                            echo "<a href='shop.php?id=" . $row['id'] . "'>" . $row["name"] . "</a>";
                            echo "</li>";

                        } else {
                            echo "<li>";
                            echo "<a href='shop.php?id=" . $row['id'] . "'>" . $row["name"] . "</a>";
                            echo "</li>";
                        }


                    }

                }
                ?>
            </ul>
            <script src="https://kit.fontawesome.com/1f95460999.js" crossorigin="anonymous"></script>
        </div>
    </section>

    <section id="products" class="section_p1">

        <div class="Product_container">
            <?php
            while ($Book_Arr = mysqli_fetch_assoc($result_book)) {
                ?>

                <a href="single_product.php?id=<?= $Book_Arr["id"] ?>">

                    <div class="product">
                        <img src="<?= $Book_Arr["cover_image"] ?>" alt="book_img">
                        <div class="describtion">
                            <span>
                                <?php
                                $id = $Book_Arr["category_id"];
                                $query = "SELECT name FROM categories WHERE id = $id";
                                $result = mysqli_query($conn, $query);
                                if ($result) {
                                    $Cat_Name = mysqli_fetch_assoc($result);
                                    if ($Cat_Name) {
                                        echo $Cat_Name["name"];
                                    } else {
                                        echo "Category not found";
                                    }
                                } else {
                                    echo "Query failed: " . mysqli_error($conn);
                                }
                                ?>
                            </span>
                            <h3 id="title"><?= $Book_Arr["title"] ?></h3>
                            <div class="star">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h4><?= $Book_Arr["price"] ?> EGP</h4>
                            <a href="add_to_cart.php?id=<?=$Book_Arr["id"]?>&quantity=1"><i class="fa-solid fa-basket-shopping cart"></i></a>
                          </div>
                    </div>
                </a>

            <?php } ?>


            <!-- <td></td> -->
            <!-- <td><?= $Book_Arr["author"] ?></td> -->



        </div>

    </section>



    <footer>
        <div class="cols">
            <div class="col">
                <h3>Contact</h3>
                <p><strong>Address: </strong> Alexandria </p>
                <p><strong>Phone: </strong> +20 1097662944</p>
                <p> <strong>Hours: </strong> EveryDay: 10 am - 10 pm </p>

            </div>
            <div class="col">
                <h3>About</h3>
                <a href="">About Us</a>
                <a href="">Privacy&Policy</a>
            </div>

            <div class="col">
                <h3>Support</h3>
                <a href="signUp.php">Sign UP</a>
                <a href="" title="Must SignUp Firstly">Help</a>
            </div>


            <div class="col">
                <h3>App</h3>
                <div class="Icons_Apps">
                    <a href=""><i class="fa-brands fa-google-play"></i></a>
                    <a href=""><i class="fa-brands fa-app-store-ios"></i></a>
                </div>
                <h3>Payment Gateways</h3>
                <div class="Icons_payment">
                    <a href=""><i><i class="fa-brands fa-cc-visa"></i></i></a>
                    <a href=""><i><i class="fa-brands fa-cc-mastercard"></i></i></a>
                    <h3><strong>Follow Us</strong> </h3>
                    <div class="icons_follow">
                        <a href="" title="FaceBook Page"><i class="fa-brands fa-facebook"></i></a>
                        <a href="" title="Instagram Page"><i class="fa-brands fa-instagram"></i></a>
                        <a href="" title="YouTube Page"><i class="fa-brands fa-youtube"></i></a>
                        <a href="" title="X Page"><i class="fa-brands fa-x-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <p>&copy;<strong> Made by:</strong> Bassel Yasser </p>
    </footer>



</body>

</html>