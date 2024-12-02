<?php
session_start();
include("./connection.php");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the answer ID and question ID from the URL
$answer_id = isset($_GET['answer_id']) ? intval($_GET['answer_id']) : 0;
$question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;

// Fetch the current answer
$stmt = $conn->prepare("SELECT answer FROM answers WHERE id = ?");
$stmt->bind_param("i", $answer_id);
$stmt->execute();
$result = $stmt->get_result();
$answer = $result->fetch_assoc();

if (!$answer) {
    echo "<p>Answer not found!</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_answer = $_POST['answer'];

    // Update the answer
    $stmt = $conn->prepare("UPDATE answers SET answer = ? WHERE id = ?");
    $stmt->bind_param("si", $updated_answer, $answer_id);
    if ($stmt->execute()) {
        header("Location: view_answer.php?question_id=$question_id");
        exit();
    } else {
        echo "<p>Failed to update the answer.</p>";
    }
}

include("./role_based_header.php")
?>

<div class="container" style="margin-top: 30px;">
    <h3>Edit Answer</h3>
    <form method="POST">
        <textarea name="answer" rows="6" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo htmlspecialchars($answer['answer']); ?></textarea>
        <button type="submit" style="margin-top: 10px; background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Update Answer</button>
    </form>
</div>

<?php include("./footer.php"); ?>
