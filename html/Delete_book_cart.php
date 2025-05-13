<?php






# receive the ID
$conn=mysqli_connect("Localhost","root","","book_store");





if(!$conn){
    echo mysqli_connect_error();
    exit;
}
else{
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);


if($id){

    $qeury="Delete from `cart` where id=$id";
    if(mysqli_query($conn,$qeury)){
        header("Location:cart.php");
        exit;
    }
    else{
        echo mysqli_error($conn);
    }
}


}
mysqli_close($conn);


?>
