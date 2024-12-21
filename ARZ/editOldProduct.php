<?php 
include("./connection.php");
if(empty($_GET['PID'])){
    header("location:editProduct.php");
    }else{
    $PID=$_GET['PID'];
    echo $PID;
    }
if (
    !empty($_POST['productName']) &&
    !empty($_POST['originalPrice']) &&
    !empty($_POST['quantity']) &&
    !empty($_POST['Type']) &&
    !empty($_POST['description']) &&
    !empty($_POST['symptoms']) &&
    !empty($_POST['approvalType'])
) {
    $productName = $_POST['productName'];
    $originalPrice = $_POST['originalPrice'];
    $quantity = $_POST['quantity'];
    $page = $_POST['Type'];
    $description = $_POST['description'];
    $symptoms = $_POST['symptoms'];
    $approvalType = $_POST['approvalType'];

    
    $query = "UPDATE products 
        SET 
            name = '$productName',
            description = '$description',
            symptoms = '$symptoms',
            page = '$page',
            approval = '$approvalType',
            price = '$originalPrice',
            quantity = '$quantity'
        WHERE id = $PID
    ";
    echo $query;
    if ($conn->query($query)) {
        echo "<script>
                alert('Successfully Updated');
                window.location.href = 'editProduct.php?PID=".$PID."';
                </script>";
    } else {
        echo "Error saving product: " . $conn->error;
    }
}

?>