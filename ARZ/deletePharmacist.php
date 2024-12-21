<?php
header('Content-Type: application/json'); // Set response to JSON
session_start();
include("./connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['pharmacist_id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit();
    }

    $pharmacist_id = intval($data['pharmacist_id']);

    // Delete pharmacist from the database
    $stmt = $conn->prepare("DELETE FROM pharmacists WHERE id = ?");
    $stmt->bind_param("i", $pharmacist_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete pharmacist']);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// If not a POST request, return an error
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
exit();
?>
