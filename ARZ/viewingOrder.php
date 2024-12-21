<?php
session_start();

include("./connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the order ID from the query string
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Fetch order details
$sql_order = "SELECT o.order_date, o.total_amount, CONCAT(c.first_name, ' ', c.last_name) AS customer_name
              FROM orders o
              JOIN customers c ON o.customer_id = c.id
              WHERE o.id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();
$order = $result_order->fetch_assoc();

// Fetch order items
$sql_items = "
    SELECT p.name, oi.quantity, oi.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

include("./role_based_header.php");
?>

<div class="container mt-5">
    <h2>Order Details</h2>

    <?php if ($order): ?>
        <table class="table table-bordered">
            <tr>
                <th>Customer Name</th>
                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
            </tr>
            <tr>
                <th>Order Date</th>
                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td>$<?php echo htmlspecialchars($order['total_amount']); ?></td>
            </tr>
        </table>

        <h3>Products in This Order</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price (Each)</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_items->num_rows > 0): ?>
                    <?php while ($item = $result_items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                            <td>$<?php echo htmlspecialchars($item['quantity'] * $item['price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No items found for this order.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No details found for this order.</p>
    <?php endif; ?>

    <div class="mb-3">
        <a href="account.php" class="btn btn-effect-3 btn-white" style="margin-bottom:20px;">&larr; Back to My Account</a>
        <a href="generate_invoice.php?order_id=<?php echo $order_id; ?>" class="btn btn-success">Download Invoice (PDF)</a>
    </div>
</div>

<?php
$conn->close();
include("./pharmacist_footer.php");
?>
