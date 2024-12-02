<?php
session_start();
require('fpdf186/fpdf.php');
include('./connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the sales date from query parameters
$sale_date = isset($_GET['date']) ? $_GET['date'] : null;

// Ensure the sale_date is provided
if (!$sale_date) {
    die("Sales date is required.");
}

// Fetch sales summary for the given date
$sql_summary = "
    SELECT 
        sr.total_price AS total_sales,
        COUNT(sro.order_id) AS total_orders
    FROM sales_records sr
    LEFT JOIN sales_records_orders sro ON sr.id = sro.sales_record_id
    WHERE sr.sale_date = ?";
$stmt_summary = $conn->prepare($sql_summary);
$stmt_summary->bind_param("s", $sale_date);
$stmt_summary->execute();
$result_summary = $stmt_summary->get_result();
$summary = $result_summary->fetch_assoc();

$total_sales = $summary['total_sales'] ?? 0;
$total_orders = $summary['total_orders'] ?? 0;

// Fetch top 3 products sold on the day
$sql_top_products = "
    SELECT 
        p.name AS product_name, 
        SUM(oi.quantity) AS total_quantity
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
    WHERE DATE(o.order_date) = ?
    GROUP BY oi.product_id
    ORDER BY total_quantity DESC
    LIMIT 3";
$stmt_top_products = $conn->prepare($sql_top_products);
$stmt_top_products->bind_param("s", $sale_date);
$stmt_top_products->execute();
$result_top_products = $stmt_top_products->get_result();

// Fetch individual order details
$sql_details = "
    SELECT 
        sro.order_id, 
        o.order_date, 
        o.total_amount 
    FROM sales_records_orders sro
    JOIN orders o ON sro.order_id = o.id
    WHERE sro.sales_record_id = (SELECT id FROM sales_records WHERE sale_date = ?)";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("s", $sale_date);
$stmt_details->execute();
$result_details = $stmt_details->get_result();

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Pharmacy Header
$pdf->Cell(0, 10, 'ARZ Pharmacy - Sales Report', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);

// Sales Summary
$pdf->Cell(0, 10, 'Sales Date: ' . $sale_date, 0, 1);
$pdf->Cell(0, 10, 'Total Sales: $' . number_format($total_sales, 2), 0, 1);
$pdf->Cell(0, 10, 'Total Orders: ' . $total_orders, 0, 1);
$pdf->Ln(10);

// Top 3 Products Sold Table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Top 3 Products Sold:', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Product Name', 1);
$pdf->Cell(40, 10, 'Quantity Sold', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
while ($row = $result_top_products->fetch_assoc()) {
    $pdf->Cell(80, 10, $row['product_name'], 1);
    $pdf->Cell(40, 10, $row['total_quantity'], 1);
    $pdf->Ln();
}
$pdf->Ln(10);

// Order Details Table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Order ID', 1);
$pdf->Cell(60, 10, 'Order Date', 1);
$pdf->Cell(50, 10, 'Order Total', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
while ($order_row = $result_details->fetch_assoc()) {
    $pdf->Cell(40, 10, $order_row['order_id'], 1);
    $pdf->Cell(60, 10, $order_row['order_date'], 1);
    $pdf->Cell(50, 10, '$' . number_format($order_row['total_amount'], 2), 1);
    $pdf->Ln();

    // Fetch products for the current order
    $sql_order_items = "
        SELECT 
            p.name AS product_name, 
            oi.quantity 
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?";
    $stmt_order_items = $conn->prepare($sql_order_items);
    $stmt_order_items->bind_param("i", $order_row['order_id']);
    $stmt_order_items->execute();
    $result_order_items = $stmt_order_items->get_result();

    // Product Details for Each Order
    $pdf->SetFont('Arial', 'I', 10);
    while ($item_row = $result_order_items->fetch_assoc()) {
        $pdf->Cell(40, 10, '', 0); // Indent
        $pdf->Cell(60, 10, $item_row['product_name'], 1);
        $pdf->Cell(50, 10, 'Quantity: ' . $item_row['quantity'], 1);
        $pdf->Ln();
    }
    $pdf->Ln(5); // Add extra space between orders
}

// Output the PDF
$file_name = "Sales_Report_$sale_date.pdf";
$pdf->Output('I', $file_name);
?>
