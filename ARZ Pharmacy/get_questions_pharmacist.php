<?php
include("./connection.php");

// Initialize log file
$logFile = "logs.txt";
function logMessage($message) {
    global $logFile;
    file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] " . $message . PHP_EOL, FILE_APPEND);
}

logMessage("Fetching questions started.");

// Fetch all questions with related customer data
$sql = "SELECT q.id, q.subject, q.message, q.created_at, q.pinned, q.answered, c.first_name, c.last_name
        FROM questions q
        JOIN customers c ON q.user_id = c.id
        ORDER BY q.pinned DESC, q.created_at DESC";

logMessage("SQL query prepared: $sql");

$result = $conn->query($sql);

if (!$result) {
    logMessage("SQL query failed: " . $conn->error);
    echo json_encode(["success" => false, "message" => "Database query failed."]);
    exit;
}

$questions = [];

if ($result->num_rows > 0) {
    logMessage("Found " . $result->num_rows . " questions.");
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
} else {
    logMessage("No questions found.");
}

// Return questions as JSON
header('Content-Type: application/json');
echo json_encode($questions);

logMessage("Questions returned successfully.");
$conn->close();
?>
