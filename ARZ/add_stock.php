<?php
session_start();
ob_start();
include('./connection.php');
include('./role_based_header.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $product_id = intval($_POST['product_id']);
    $quantity_added = intval($_POST['quantity_added']);
    $batch_number = htmlspecialchars($_POST['batch_number']);
    $supplier_name = htmlspecialchars($_POST['supplier_name']);
    $supplier_contact = htmlspecialchars($_POST['supplier_contact']);
    $cost_price = floatval($_POST['cost_price']);
    $selling_price = floatval($_POST['selling_price']);

    // Insert into stock_records
    $insert_query = "
        INSERT INTO stock_records (
            product_id, quantity_added, batch_number, 
            supplier_name, supplier_contact, cost_price, 
            selling_price
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($insert_query);

    if ($stmt) {
        $stmt->bind_param(
            'iisssds',
            $product_id,
            $quantity_added,
            $batch_number,
            $supplier_name,
            $supplier_contact,
            $cost_price,
            $selling_price
        );
        if ($stmt->execute()) {
            header('Location: stock.php'); // Redirect to stock records page
            exit;
        } else {
            error_log("Error adding stock: " . $stmt->error);
            echo "Error adding stock: " . $stmt->error;
            exit;
        }
    } else {
        error_log("Error preparing query: " . $conn->error);
        echo "Error preparing query: " . $conn->error;
        exit;
    }
}

ob_end_flush(); // Flush the output buffer

// Fetch products for the dropdown
$products_query = "SELECT id, name FROM products";
$products_result = $conn->query($products_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-3">Add Stock</h1>
    <form method="POST" action="add_stock.php">
        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select class="form-select" id="product_id" name="product_id" required>
                <option value="">Select Product</option>
                <?php while ($product = $products_result->fetch_assoc()): ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity_added" class="form-label">Quantity Added</label>
            <input type="number" class="form-control" id="quantity_added" name="quantity_added" required>
        </div>
        <div class="mb-3">
            <label for="batch_number" class="form-label">Batch Number</label>
            <input type="text" class="form-control" id="batch_number" name="batch_number" required>
        </div>
        <div class="mb-3">
            <label for="supplier_name" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" id="supplier_name" name="supplier_name" required>
        </div>
        <div class="mb-3">
            <label for="supplier_contact" class="form-label">Supplier Contact</label>
            <input type="text" class="form-control" id="supplier_contact" name="supplier_contact">
        </div>
        <div class="mb-3">
            <label for="cost_price" class="form-label">Cost Price</label>
            <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" required>
        </div>
        <div class="mb-3">
            <label for="selling_price" class="form-label">Selling Price</label>
            <input type="number" step="0.01" class="form-control" id="selling_price" name="selling_price" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Stock</button>
    </form>
</div>
</body>
</html>
