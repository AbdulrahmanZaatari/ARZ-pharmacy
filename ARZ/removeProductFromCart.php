<?php
if(empty($_GET['PID'])){
    header('location:cart.php');
}else{
    include "./connection.php";
    $PID=$_GET['PID'];
    session_start();
    $UID=$_SESSION['user_id'];
    $delete="delete from cart where product_id=$PID 
    and customer_id=$UID";
    $result=mysqli_query($conn,$delete);
    echo "<script> alert('Item removed from your cart') </script>";
    header("refresh:1,url=cart.php");
}
?>