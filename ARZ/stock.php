<?php
session_start();
include('./connection.php');
include('./role_based_header.php');

// Enable logging to a file
function log_message($message) {
    file_put_contents('error_log.txt', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

// Fetch stock records from the database
$stock_records_query = "
    SELECT 
        sr.batch_number, 
        p.name, 
        sr.added_date 
    FROM 
        stock_records sr 
    INNER JOIN 
        products p 
    ON 
        sr.product_id = p.id 
    ORDER BY 
        sr.added_date DESC
";
$stock_records_result = $conn->query($stock_records_query);

if (!$stock_records_result) {
    log_message("Error fetching stock records: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Records</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-3">Stock Records</h1>

    <!-- Add Stock Button -->
    <a href="add_stock.php" class="btn btn-success mb-4">Add Stock</a>

    <?php if ($stock_records_result && $stock_records_result->num_rows > 0): ?>
        <!-- Display stock records if available -->
        <?php while ($row = $stock_records_result->fetch_assoc()): ?>
            <div class="stock-record mb-4 p-3 border rounded">
                <h3>Batch Number: <?php echo htmlspecialchars($row['batch_number']); ?></h3>
                <p>Product Name: <?php echo htmlspecialchars($row['name']); ?></p>
                <p>Date Added: <?php echo htmlspecialchars($row['added_date']); ?></p>
                <a href="view_stock_details.php?batch=<?php echo urlencode($row['batch_number']); ?>" class="btn btn-primary">View Details</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <!-- Display a message when no stock records exist -->
        <p>No stock records found.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
