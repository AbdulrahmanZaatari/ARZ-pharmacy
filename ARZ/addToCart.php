<?php
include("./connection.php");
session_start();

// Retrieve product ID and customer ID
$PID = $_GET['PID'];
$UID = $_SESSION['user_id'];

// Query to get product details
$query = "SELECT name, price, image_path, approval FROM products WHERE id='$PID'";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<script>alert('Product not found!');</script>";
    header("refresh:1,url=shop.php");
    exit();
}

$name = $product['name'];
$price = $product['price'];
$image_path = $product['image_path'];
$approval = $product['approval'];

// Check if product already exists in the cart
$checkFirst = "SELECT * FROM cart WHERE customer_id='$UID' AND product_id='$PID'";
$result = mysqli_query($conn, $checkFirst);

if (mysqli_num_rows($result) > 0) {
    echo "<script>
        alert('Product already exists in your cart!');    
    </script>";
    header("refresh:1,url=product-details.php?PID=$PID");
    exit();
}

// Handle approval logic
if ($approval === "no approval needed") {
    // Add product to cart directly
    $insert = "INSERT INTO cart (customer_id, product_id, price, name, image_path) 
               VALUES ('$UID', '$PID', '$price', '$name', '$image_path')";
    if (mysqli_query($conn, $insert)) {
        echo "<script>
            alert('Product added to cart successfully!');    
        </script>";
    } else {
        echo "<script>
            alert('Failed to add product to cart.');    
        </script>";
    }
    header("refresh:1,url=product-details.php?PID=$PID");
} else {
    // Redirect to approval request creation
    echo "<script>
        alert('This product requires approval. A request will be sent to the pharmacist.');    
    </script>";
    header("refresh:1,url=createApproval.php?PID=$PID");
}
?>
