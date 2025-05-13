<?php

session_start();

if (empty($_SESSION['id'])) {
    header("Location: login.php");

}



$conn = mysqli_connect("Localhost", "root", "", "book_store");
if (!$conn) {
    echo mysqli_connect_error();
    exit;
} else {
    $qeury = "SELECT
    c.id AS book_id,
    u.name AS user_name,
    b.title AS book_title,
    b.cover_image AS book_image,
    b.price AS book_price,
    c.quantity AS quantity
FROM
    Cart c
    JOIN Users u ON c.user_id = u.id
    JOIN Books b ON c.book_id = b.id
    where u.id=". $_SESSION['id'] ." and c.quantity>0 ;
";
    $result_book = mysqli_query($conn, $qeury);

}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="describtion" content="This is the best book store at ever">
    <link rel="stylesheet" href="../media/icons/fontawesome-free-6.5.2-web/css/all.css">
    <link rel="stylesheet" href="../style/cart.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>

<body>
    <header>
        <a href="shop.php"> <img src="../media/icons/Screenshot 2024-04-26 142359.png" alt="logo" class="logo"></a>

        <div>
            <ul id="NavBar">
                <li><a href="shop.php">Shop</a></li>
                <li><a href="mailto:basel.yaser205@gamil.com">Contact</a></li>
                <li><a href="cart.php" class="active"><i class="fa-solid fa-cart-shopping"></i></a>
                </li>
            </ul>
            <script src="https://kit.fontawesome.com/1f95460999.js" crossorigin="anonymous"></script>
        </div>
    </header>



    <section id="cart">
        <h2>Cart</h2>
        <table>
            <thead>

                <tr>
                    <td>Remove</td>
                    <td>Book</td>
                    <td>Image</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Subtotal</td>
                </tr>
            </thead>

            <tbody>
                <?php
                while ($Book_Arr = mysqli_fetch_assoc($result_book)) {
                    ?>
                    <tr>
                        <td><a href="Delete_book_cart.php?id=<?= $Book_Arr["book_id"] ?>"><i
                                    class="fa-solid fa-circle-minus delete"></i></a></td>
                        <td><?= $Book_Arr["book_title"] ?></td>
                        <td><a href="<?= $Book_Arr["book_image"] ?>" target="_blank"><img
                                    src="<?= $Book_Arr["book_image"] ?>" alt="book_img"></a></td>
                        <td><?= $Book_Arr["book_price"] ?></td>
                        <td><?= $Book_Arr["quantity"] ?></td>
                        <td><?= $Book_Arr["quantity"]*$Book_Arr["book_price"] ?></td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
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
                <a href="">Sign UP</a>
                <a href="">Help</a>
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
