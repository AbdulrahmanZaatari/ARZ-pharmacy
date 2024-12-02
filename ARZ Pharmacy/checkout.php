<?php
ob_start(); // Start output buffering
session_start();
include "./connection.php";
include "./role_based_header.php";

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Redirect to cart if no products or quantities are provided
if (empty($_POST['product_ids']) || empty($_POST['quantities'])) {
    header("Location: cart.php");
    exit();
}

$UID = $_SESSION['user_id'];
$product_ids = $_POST['product_ids']; // Array of product IDs
$quantities = array_map('intval', $_POST['quantities']); // Sanitize and ensure integers
$total = 0;
$order_items = []; // To store items for the order_items table

// Calculate the total amount and prepare order_items
foreach ($product_ids as $index => $PID) {
    $qty = intval($quantities[$index]);
    
    // Fetch the product price
    $query = "SELECT price FROM products WHERE id='$PID'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error fetching product price: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    $price = floatval($row['price']);
    
    // Calculate subtotal for this product
    $subtotal = $price * $qty;
    $total += $subtotal;

    // Prepare the data for order_items
    $order_items[] = [
        'product_id' => $PID,
        'quantity' => $qty,
        'price' => $price
    ];
}

// Insert a single order into the orders table
$insert_order_query = "INSERT INTO orders (customer_id, total_amount) VALUES ('$UID', '$total')";
if (!mysqli_query($conn, $insert_order_query)) {
    die("Error inserting order: " . mysqli_error($conn));
}

// Get the newly created order ID
$order_id = mysqli_insert_id($conn);

// Insert each product into the order_items table
foreach ($order_items as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    // Insert data into order_items table
    $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                          VALUES ('$order_id', '$product_id', '$quantity', '$price')";
    if (!mysqli_query($conn, $insert_item_query)) {
        die("Error inserting order item: " . mysqli_error($conn));
    }
}

// Get the current date
$order_date = date('Y-m-d');

// Check if a sales record exists for the current date
$sales_record_query = "SELECT id FROM sales_records WHERE sale_date = ?";
$stmt_sales_record = $conn->prepare($sales_record_query);
$stmt_sales_record->bind_param('s', $order_date);
$stmt_sales_record->execute();
$sales_record_result = $stmt_sales_record->get_result();

if ($sales_record_result->num_rows > 0) {
    // If the sales record exists, get the ID and update the total price
    $sales_record = $sales_record_result->fetch_assoc();
    $sales_record_id = $sales_record['id'];

    $update_sales_record_query = "UPDATE sales_records SET total_price = total_price + ? WHERE id = ?";
    $stmt_update_sales_record = $conn->prepare($update_sales_record_query);
    $stmt_update_sales_record->bind_param('di', $total, $sales_record_id);
    $stmt_update_sales_record->execute();
} else {
    // If the sales record does not exist, create a new one
    $insert_sales_record_query = "INSERT INTO sales_records (sale_date, total_price) VALUES (?, ?)";
    $stmt_insert_sales_record = $conn->prepare($insert_sales_record_query);
    $stmt_insert_sales_record->bind_param('sd', $order_date, $total);
    $stmt_insert_sales_record->execute();
    $sales_record_id = $stmt_insert_sales_record->insert_id;
}

// Insert into sales_records_orders table
$insert_sales_records_orders_query = "INSERT INTO sales_records_orders (sales_record_id, order_id, order_date, order_total)
                                      VALUES (?, ?, ?, ?)";
$stmt_insert_sales_records_orders = $conn->prepare($insert_sales_records_orders_query);
$stmt_insert_sales_records_orders->bind_param('iisd', $sales_record_id, $order_id, $order_date, $total);
$stmt_insert_sales_records_orders->execute();

$daily_sales_query = "SELECT id FROM daily_sales WHERE sale_date = ?";
$stmt_daily_sales = $conn->prepare($daily_sales_query);
$stmt_daily_sales->bind_param('s', $order_date);
$stmt_daily_sales->execute();
$daily_sales_result = $stmt_daily_sales->get_result();

if ($daily_sales_result->num_rows > 0) {
    // If the daily_sales record exists, update total_sales and total_orders
    $update_daily_sales_query = "UPDATE daily_sales 
                                 SET total_sales = total_sales + ?, 
                                     total_orders = total_orders + 1 
                                 WHERE sale_date = ?";
    $stmt_update_daily_sales = $conn->prepare($update_daily_sales_query);
    $stmt_update_daily_sales->bind_param('ds', $total, $order_date);
    $stmt_update_daily_sales->execute();
} else {
    // If the daily_sales record does not exist, create a new one
    $total_orders = 1; // Initial total orders for the day
    $insert_daily_sales_query = "INSERT INTO daily_sales (sale_date, total_sales, total_orders) VALUES (?, ?, ?)";
    $stmt_insert_daily_sales = $conn->prepare($insert_daily_sales_query);
    $stmt_insert_daily_sales->bind_param('sdi', $order_date, $total, $total_orders);
    $stmt_insert_daily_sales->execute();
}
// Clear the cart after processing
$delete_query = "DELETE FROM cart WHERE customer_id='$UID'";
if (!mysqli_query($conn, $delete_query)) {
    die("Error clearing cart: " . mysqli_error($conn));
}

// Redirect to a confirmation page or back to the cart
header("Location: confirmation.php?order_id=$order_id");
exit();

ob_end_flush(); // End output buffering
?>
