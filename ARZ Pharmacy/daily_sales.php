<?php
session_start();
include('./connection.php');
include('./role_based_header.php');

// Enable logging to a file
function log_message($message) {
    file_put_contents('error_log.txt', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

// Fetch all dates with sales
$sales_dates_query = "SELECT DISTINCT sale_date FROM sales_records ORDER BY sale_date DESC";
$sales_dates_result = $conn->query($sales_dates_query);

if (!$sales_dates_result) {
    log_message("Error fetching sales dates: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-3">Sales Records</h1>

    <?php if ($sales_dates_result && $sales_dates_result->num_rows > 0): ?>
        <?php while ($row = $sales_dates_result->fetch_assoc()): ?>
            <?php 
                $date = $row['sale_date'];
                // Fetch total sales for the date
                $total_query = "SELECT total_price FROM sales_records WHERE sale_date = ?";
                $stmt_total = $conn->prepare($total_query);
                if (!$stmt_total) {
                    log_message("Error preparing total query: " . $conn->error);
                    continue;
                }
                $stmt_total->bind_param('s', $date);
                if (!$stmt_total->execute()) {
                    log_message("Error executing total query: " . $stmt_total->error);
                    continue;
                }
                $total_result = $stmt_total->get_result()->fetch_assoc();
                $total_sales = $total_result['total_price'] ?? 0;
            ?>
            <div class="sales-record" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 5px;">
                <h3>Sales for <?php echo $date; ?></h3>
                <p>Total Sales: $<?php echo number_format($total_sales, 2); ?></p>
                <a href="generate_sales_report.php?date=<?php echo $date; ?>" class="btn btn-success">Download PDF</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No sales records found.</p>
    <?php endif; ?>
</div>
</body>
</html>
