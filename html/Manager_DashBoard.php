<?php

session_start();

if(empty($_SESSION['id'])){
    header("Location: login.php");

}





$conn=mysqli_connect("Localhost","root","","Book_store");
if(!$conn){
    echo mysqli_connect_error();
    exit;
}

?>




<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../media/icons/fontawesome-free-6.5.2-web/css/all.css">
    <link rel="stylesheet" href="../style/Manager_DashBoard2.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dash Borad</title>
</head>

<body>
    <header>
        <a href="Index.html"> <img src="../media/icons/Screenshot 2024-04-26 142359.png" alt="logo" class="logo"></a>
        <div>
            <ul id="NavBar">
                <li><a href="shop.php">Shop</a></li>
                <li><a href="logout.php">Log out</a></li>

            </ul>
            <a href="../media/avatar/WhatsApp Image 2024-04-28 at 17.29.22_9df423cb.jpg"> <img
                    src="../media/avatar/WhatsApp Image 2024-04-28 at 17.29.22_9df423cb.jpg" alt="avatar" class="active"
                    id="avatar"></a>
        </div>
    </header>



    <section class="content">

        <section class="analytics">
            <h2>Analytics</h2>
        <div class="boxs">
            <div>
                <?php 
                $query = "SELECT COUNT(*) AS total FROM books";
                $result = mysqli_query($conn, $query);
                $total_books = mysqli_fetch_assoc($result);
                
                ?>
                <h1>#Books</h1>
                <p><?= $total_books['total']?></p>
            </div>
            <div>
                <h1>#Sold_Books</h1>
                <p>50</p>
            </div>
            <div>
            <?php 
                $query = "SELECT COUNT(*) AS total FROM users where role='c'";
                $result = mysqli_query($conn, $query);
                $total_books = mysqli_fetch_assoc($result);
                ?>
                <h1>#Customers</h1>
                <p><?= $total_books['total']?></p>
            </div>
        </section>
        

      
         <section id="books">
            <?php 
            if(isset($_POST["B_Search"])){
                $search=mysqli_escape_string($conn,$_POST["B_Search"]);
                  $qeury = "SELECT * FROM `books` WHERE (title LIKE '%".$search."%' OR author LIKE '%".$search."%')";
                }
                else{ 
                #Select all users
                // $qeury="select * from `user` ";
                $qeury="select * from `books` ";
                
                }
                $result_book=mysqli_query($conn,$qeury);
                
            ?>
            <h2>Books</h2>
            <form method="post" id="search_box">
   <input type="text" name="B_Search" placeholder="Search" class="search"
   value="<?php echo !empty($_POST["B_Search"]) ? $_POST["B_Search"] : "" ?>">
        <input type="submit" value="search" class="button">
        <a href="Upload_book.php" title="add book">New</a>
    
   </form>

            <table>
                <thead>
                    
                        <tr>
                            <td>Remove</td>
                            <td>Edit</td>
                            <td>Book</td>
                            <td>Author</td>
                            <td>Image</td>
                            <td>Price</td>
                            <td>created At</td>
                            <td>Updated At</td>
                            
                        </tr>
                </thead>
    
                <tbody>
                <?php
                while($Book_Arr=mysqli_fetch_assoc($result_book)){
                   ?>
                    <tr>
                        <td><a href="Delete_book.php?id=<?=$Book_Arr["id"]?>"><i class="fa-solid fa-circle-minus delete"></i></a></td>
                        <td><a href="Delete_book.php?id=<?=$Book_Arr["id"]?>"><i class="fa-solid fa-pen-to-square edit"></i></a></td>
                        <td><?=$Book_Arr["title"] ?></td>
                        <td><?=$Book_Arr["author"] ?></td>
                        <td><a href="<?= $Book_Arr["cover_image"]?>" target="_blank"><img src="<?= $Book_Arr["cover_image"]?>"  alt="book_img" ></a></td>
                        <td><?=$Book_Arr["price"] ?></td>
                        <td><?=$Book_Arr["created_at"] ?></td>
                        <td><?=$Book_Arr["updated_at"] ?></td>
                    </tr>

                <?php }?>
                    
                </tbody>
            </table>
            


        </section>
       
    
        <section id="user">

        <?php 
        if(isset($_POST["U_Search"])){
            $search=mysqli_escape_string($conn,$_POST["U_Search"]);
              $qeury = "SELECT * FROM `users` WHERE (name LIKE '%".$search."%' OR email LIKE '%".$search."%') and role!='A'";
            }
            else{ 
            #Select all users
            // $qeury="select * from `user` ";
            $qeury="select * from `users` where role!='A'";
            
            }
            $result_book=mysqli_query($conn,$qeury);
            
        ?>


            <h2>Users</h2>
            <form method="post" id="search_box">
                <input type="search" placeholder="Search" class="search" name="U_Search"
                value="<?php echo !empty($_POST["U_Search"]) ? $_POST["U_Search"] : ""; ?>"
                > 
                    <!-- <i class="fa-solid fa-magnifying-glass"></i> -->
                <input type="submit" value="search" class="button" >
            </form>
    
            <table>
                <thead>
                    
                        <tr>
                            <td>Remove</td>
                            <td>Edit</td>
                            <td>Name</td>
                            <td>Email</td>
                            <td>Joind At</td>
                            <td>Modified At</td>
                        </tr>
                </thead>
    
                <tbody>
                <?php
                while($user_Arr=mysqli_fetch_assoc($result_book)){
                   ?>
                    <tr>
                        <td><a href="Delete_user.php?id=<?=$user_Arr["id"]?>"><i class="fa-solid fa-circle-minus delete"></i></a></td>
                        <td><a href="Delete_book.php?id=<?=$user_Arr["id"]?>"><i class="fa-solid fa-pen-to-square edit"></i></a></td>
                        <td><?=$user_Arr["name"] ?></td>
                        <td><?=$user_Arr["email"] ?></td>
                        <td><?=$user_Arr["created_at"] ?></td>
                        <td><?=$user_Arr["updated_at"] ?></td>
                    </tr>

                <?php }?> 
                </tbody>
            </table>
        </section>


    </section>







    
    <footer>
        <div class="cols">
          <div class="col">
              <h3>Contact</h3>
              <p><strong>Address: </strong>  Alexandria </p>
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