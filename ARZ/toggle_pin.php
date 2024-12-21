<?php
session_start();
include("./connection.php");

// Check if the user is authenticated as a pharmacist
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pharmacist') {
    error_log("Unauthorized access attempt.");
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Decode JSON input (since fetch sends JSON payload)
$data = json_decode(file_get_contents('php://input'), true);

// Log the raw JSON data received
error_log("Raw JSON received: " . print_r($data, true));

// Extract question ID and pin status from the request
$question_id = isset($data['question_id']) ? intval($data['question_id']) : 0;
$pinned = isset($data['pinned']) ? intval($data['pinned']) : 0;

// Log extracted values
error_log("Extracted question_id: $question_id, pinned: $pinned");

// Validate the input
if ($question_id <= 0) {
    error_log("Invalid question_id: $question_id");
    echo json_encode(['success' => false, 'message' => 'Invalid question ID']);
    exit;
}

// Prepare the SQL query to update the pin status
$sql = "UPDATE questions SET pinned = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind the parameters and execute the query
    $stmt->bind_param("ii", $pinned, $question_id);
    if ($stmt->execute()) {
        error_log("Pin status updated successfully for question_id: $question_id with pinned: $pinned");
        echo json_encode(['success' => true, 'message' => 'Pin status updated successfully']);
    } else {
        error_log("Query execution failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    error_log("Database error: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>
