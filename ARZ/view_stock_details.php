<?php
session_start();
include('./connection.php');
include('./role_based_header.php');

// Enable logging to a file
function log_message($message) {
    file_put_contents('error_log.txt', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

// Validate batch number from query string
if (!isset($_GET['batch']) || empty($_GET['batch'])) {
    die("Batch number is required.");
}

$batch_number = $_GET['batch'];

// Fetch stock details for the given batch number
$stock_details_query = "
    SELECT 
        sr.batch_number, 
        p.name AS product_name, 
        sr.quantity_added, 
        sr.supplier_name, 
        sr.supplier_contact, 
        sr.cost_price,  
        sr.selling_price,  
        sr.added_date 
    FROM 
        stock_records sr 
    INNER JOIN 
        products p 
    ON 
        sr.product_id = p.id 
    WHERE 
        sr.batch_number = ?
";

$stmt = $conn->prepare($stock_details_query);

if (!$stmt) {
    log_message("Error preparing statement: " . $conn->error);
    die("An error occurred while preparing the query.");
}

$stmt->bind_param('s', $batch_number);

if (!$stmt->execute()) {
    log_message("Error executing query: " . $stmt->error);
    die("An error occurred while fetching stock details.");
}

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No stock details found for this batch.");
}

$stock_details = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Stock Details</h1>
    <div class="stock-details p-4 border rounded shadow">
        <p><strong>Batch Number:</strong> <?php echo htmlspecialchars($stock_details['batch_number']); ?></p>
        <p><strong>Product Name:</strong> <?php echo htmlspecialchars($stock_details['product_name']); ?></p>
        <p><strong>Quantity Added:</strong> <?php echo htmlspecialchars($stock_details['quantity_added']); ?></p>
        <p><strong>Supplier Name:</strong> <?php echo htmlspecialchars($stock_details['supplier_name']); ?></p>
        <p><strong>Supplier Contact:</strong> <?php echo htmlspecialchars($stock_details['supplier_contact']); ?></p>
        <p><strong>Cost Price:</strong> $<?php echo number_format($stock_details['cost_price'], 2); ?></p>
        <p><strong>Selling Price:</strong> $<?php echo number_format($stock_details['selling_price'], 2); ?></p>
        <p><strong>Date Added:</strong> <?php echo htmlspecialchars($stock_details['added_date']); ?></p>
    </div>
    <a href="stock.php" class="btn btn-primary mt-3">Back to Stock List</a>
</div>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
