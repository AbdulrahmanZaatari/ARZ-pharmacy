<?php
session_start();
include("./connection.php");

// Ensure user is a pharmacist
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pharmacist') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $question_id = isset($_POST['question_id']) ? intval($_POST['question_id']) : 0;
    $pinned = isset($_POST['pinned']) ? intval($_POST['pinned']) : 0;

    // Debugging: Log received values
    error_log("Received question_id: $question_id, pinned: $pinned");

    if ($question_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid question ID']);
        exit;
    }

    // Update the pinned status in the database
    $sql = "UPDATE questions SET pinned = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $pinned, $question_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Pin status updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
$conn->close();
?>
