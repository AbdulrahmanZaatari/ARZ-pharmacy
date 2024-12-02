<?php
session_start();

include("./connection.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get question details
$question_id = isset($_GET['question_id']) ? (int) $_GET['question_id'] : 0;
$sql_answer = "
    SELECT questions.subject, questions.message, answers.answer 
    FROM questions 
    LEFT JOIN answers ON questions.id = answers.question_id 
    WHERE questions.id = ? AND questions.email = ?";
$stmt_answer = $conn->prepare($sql_answer);
$stmt_answer->bind_param("is", $question_id, $_SESSION['email']);
$stmt_answer->execute();
$result_answer = $stmt_answer->get_result();
$question = $result_answer->fetch_assoc();

include("./role_based_header.php")
?>

<div class="container mt-5">
    <h2>Question Details</h2>
    <?php if ($question): ?>
        <p><strong>Subject:</strong> <?php echo htmlspecialchars($question['subject']); ?></p>
        <p><strong>Message:</strong> <?php echo htmlspecialchars($question['message']); ?></p>
        <p><strong>Answer:</strong> <?php echo htmlspecialchars($question['answer'] ?: 'No answer provided yet.'); ?></p>
    <?php else: ?>
        <p>No question found or you do not have access to this question.</p>
    <?php endif; ?>

    <!-- Back to Questions Button -->
    <div class="mt-4">
        <a href="account.php" class="btn btn-effect-3 btn-white" style="margin-bottom:20px;">Back to Questions</a>
    </div>
</div>

<?php
$conn->close();
include("./footer.php");
?>
