<?php
session_start();
include("./connection.php");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is a pharmacist
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pharmacist') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get question_id from the request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
    $question_id = intval($_POST['question_id']);

    // Delete the question
    $sql = "DELETE FROM questions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        header("Location: pharmacist_account.php"); // Redirect to the questions list after deletion
        exit();
    } else {
        echo "Error deleting question: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
$conn->close();
?>
