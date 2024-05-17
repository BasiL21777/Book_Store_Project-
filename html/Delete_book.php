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


    $query = "SELECT cover_image FROM books WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $image_path =$_SERVER['DOCUMENT_ROOT'] . $row['cover_image'];

    echo $image_path;
    
    // Delete the file from the uploaded folder
    if (file_exists($image_path)) {
        unlink($image_path);
    } else {
        echo 'File not found.';
    }


    $qeury="Delete from `books` where id=$id";
    if(mysqli_query($conn,$qeury)){
        header("Location:Manager_DashBoard.php");
        exit;
    }
    else{
        echo mysqli_error($conn);
    }
}


}
mysqli_close($conn);


?>
