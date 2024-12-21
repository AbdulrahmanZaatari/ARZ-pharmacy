<?php
session_start();
require('fpdf186/fpdf.php');
include('./connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the order ID
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

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Pharmacy Header
$pdf->Cell(0, 10, 'ARZ Pharmacy', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);

// Customer and Order Info
$pdf->Cell(0, 10, 'Customer Name: ' . $order['customer_name'], 0, 1);
$pdf->Cell(0, 10, 'Order Date: ' . $order['order_date'], 0, 1);
$pdf->Cell(0, 10, 'Total Amount: $' . number_format($order['total_amount'], 2), 0, 1);
$pdf->Ln(10);

// Order Items Table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Product Name', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(40, 10, 'Price (Each)', 1);
$pdf->Cell(40, 10, 'Total Price', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
while ($item = $result_items->fetch_assoc()) {
    $pdf->Cell(80, 10, $item['name'], 1);
    $pdf->Cell(30, 10, $item['quantity'], 1);
    $pdf->Cell(40, 10, '$' . number_format($item['price'], 2), 1);
    $pdf->Cell(40, 10, '$' . number_format($item['quantity'] * $item['price'], 2), 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output('I', 'Invoice_Order_' . $order_id . '.pdf');
?>
