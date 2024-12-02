<?php
session_start();
include "./connection.php";
include "./role_based_header.php";

$order_id = intval($_GET['order_id']);

// Fetch order details
$order_query = "SELECT * FROM orders WHERE id='$order_id'";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

// Fetch order items
$items_query = "SELECT oi.quantity, oi.price, p.name 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id='$order_id'";
$items_result = mysqli_query($conn, $items_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .confirmation-container {
            margin: 30px auto;
            max-width: 800px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .confirmation-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .confirmation-header h2 {
            font-weight: bold;
            color: #333333;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-details p {
            margin: 5px 0;
            font-size: 16px;
        }
        .order-items-table th {
            background-color: #6c757d;
            color: #ffffff;
        }
        .order-items-table td, .order-items-table th {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container confirmation-container">
    <div class="confirmation-header">
        <h2>Order Confirmation</h2>
    </div>
    <div class="order-details">
        <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
        <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
        <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
    </div>

    <h3 class="mb-3">Order Items</h3>
    <table class="table table-bordered order-items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($items_result)) { ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
