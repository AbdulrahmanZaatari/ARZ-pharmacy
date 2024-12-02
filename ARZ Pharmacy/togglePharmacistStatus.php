<?php
header('Content-Type: application/json'); // Set the response to JSON
session_start();
include("./connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['pharmacist_id']) || !isset($data['current_status'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit();
    }

    $pharmacist_id = intval($data['pharmacist_id']);
    $current_status = $data['current_status'];

    // Determine the new status
    $new_status = ($current_status === 'active') ? 'blocked' : 'active';

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE pharmacists SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $pharmacist_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// If not a POST request, return an error
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
exit();
