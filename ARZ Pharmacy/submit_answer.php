<?php
session_start();

include("./connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pharmacist') {
    echo "Unauthorized access. Only pharmacists can submit answers.";
    exit;
}
// Validate input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = isset($_POST['question_id']) ? intval($_POST['question_id']) : 0;
    $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';

    if ($question_id === 0 || empty($answer)) {
        echo "<p>Invalid question ID or empty answer.</p>";
        exit;
    }

    // Insert or update the answer in the answers table
    $sql = "INSERT INTO answers (question_id, answer, created_at) VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE answer = VALUES(answer), created_at = NOW()";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("is", $question_id, $answer);

    if ($stmt->execute()) {
        echo "<p>Answer submitted successfully!</p>";
        header("Location: view_answer.php?question_id=" . $question_id);
        exit;
    } else {
        echo "<p>Error submitting the answer: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p>Invalid request method.</p>";
}

$conn->close();
?>
