<?php
session_start();
include("./connection.php");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the role variable
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

include("./role_based_header.php");

// Get the question ID from the URL
$question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;

// Fetch the question details
$sql_question = "SELECT id, subject, message, pinned, created_at FROM questions WHERE id = ?";
$stmt_question = $conn->prepare($sql_question);
$stmt_question->bind_param("i", $question_id);
$stmt_question->execute();
$result_question = $stmt_question->get_result();
$question = $result_question->fetch_assoc();

if (!$question) {
    echo "<p>Question not found!</p>";
    exit;
}

// Fetch the answer for this question
$sql_answer = "SELECT id, answer FROM answers WHERE question_id = ?";
$stmt_answer = $conn->prepare($sql_answer);
$stmt_answer->bind_param("i", $question_id);
$stmt_answer->execute();
$result_answer = $stmt_answer->get_result();
$answer = $result_answer->fetch_assoc();
?>

<!-- Placeholder Image Section -->
<div style="text-align: center; margin-top: 20px;">
    <img src="img/bg/AQ2.jpeg" alt="Question Image" style="width: 80%; max-width: 600px; height: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
</div>

<!-- Question Details Section -->
<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-lg-12">
            <!-- Question and Answer Box -->
            <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <h3 style="font-size: 28px; font-weight: bold; color: #2c3e50;"><?php echo htmlspecialchars($question['subject']); ?></h3>
                <p style="color: #555; font-size: 16px; margin-top: 10px;"><?php echo nl2br(htmlspecialchars($question['message'])); ?></p>
                <p style="font-size: 14px; color: #aaa; margin-top: 15px;">Posted on: <?php echo date("F d, Y", strtotime($question['created_at'])); ?></p>

                <!-- Pin/Unpin Button -->
                <?php if ($role === 'pharmacist'): ?>
<div style="margin-top: 10px;">
    <form method="POST" action="toggle_pin.php" style="display: inline;" id="pinForm">
        <!-- Hidden inputs to store question details -->
        <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
        <input type="hidden" name="pinned" id="pinnedInput" value="<?php echo $question['pinned'] == 1 ? '0' : '1'; ?>">
        <!-- Button to pin or unpin -->
        <button type="button" id="pinButton" style="background-color: #ffc107; color: black; padding: 10px 20px; border: none; border-radius: 5px;">
            <?php echo $question['pinned'] == 1 ? 'Unpin' : 'Pin'; ?>
        </button>
    </form>
</div>

<script>
document.getElementById('pinButton').addEventListener('click', async function (event) {
    event.preventDefault(); // Prevent default form submission

    // Get question details from the form
    const form = document.getElementById('pinForm');
    const questionId = form.querySelector('[name="question_id"]').value;
    const pinned = form.querySelector('[name="pinned"]').value;

    try {
        // Log data being sent to the backend
        console.log("Sending data to backend:", { question_id: questionId, pinned: pinned });

        // Send the data via fetch to the PHP backend
        const response = await fetch('toggle_pin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question_id: questionId, pinned: pinned })
        });

        const result = await response.json();

        // Log the response from the backend
        console.log("Response from backend:", result);

        if (result.success) {
            // Toggle the pinned state and update button text dynamically
            const pinInput = form.querySelector('[name="pinned"]');
            const pinButton = document.getElementById('pinButton');

            // Reverse the `pinned` state based on the current value
            if (pinned === '1') {
                pinInput.value = '0'; // Update hidden input value
                pinButton.textContent = 'Pin'; // Update button text
            } else {
                pinInput.value = '1'; // Update hidden input value
                pinButton.textContent = 'Unpin'; // Update button text
            }

            // Log the updated values for debugging
            console.log("Updated pinned value:", pinInput.value);
        } else {
            alert(result.message); // Show an error message if the operation fails
        }
    } catch (error) {
        console.error('Error:', error); // Log the error for debugging
        alert('An error occurred. Please try again.');
    }
});
</script>
<?php endif; ?>


                <!-- Delete Question Button -->
                <?php if ($role === 'pharmacist'): ?>
                    <div style="margin-top: 10px;">
                        <form method="POST" action="delete_question.php" style="display: inline;">
                            <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
                            <button type="submit" style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px;" onclick="return confirm('Are you sure you want to delete this question?');">Delete Question</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Answer Box -->
            <div style="background-color: #f9f9f9; margin-top: 30px; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <h4 style="font-size: 22px; font-weight: bold; color: #27ae60;">Answer:</h4>
                <?php if ($answer): ?>
                    <p style="color: #555; font-size: 16px; margin-top: 10px;"><?php echo nl2br(htmlspecialchars($answer['answer'])); ?></p>
                    <!-- Edit Answer Button -->
                    <?php if ($role === 'pharmacist'): ?>
                        <div style="margin-top: 10px;">
                            <form method="GET" action="edit_answer.php" style="display: inline;">
                                <input type="hidden" name="answer_id" value="<?php echo $answer['id']; ?>">
                                <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
                                <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Edit Answer</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="color: gray; font-size: 16px; margin-top: 10px;"><em>No answer yet.</em></p>
                <?php endif; ?>
            </div>

            <!-- Answer Input -->
            <?php if ($role === 'pharmacist' && !$answer): ?>
                <div style="background-color: #f1f1f1; margin-top: 30px; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <h4 style="font-size: 22px; font-weight: bold; color: #007bff;">Submit Your Answer:</h4>
                    <form method="POST" action="submit_answer.php">
                        <textarea name="answer" rows="4" placeholder="Type your answer here..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required></textarea>
                        <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
                        <button type="submit" style="margin-top: 10px; background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Submit Answer</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$conn->close();
include("./footer.php");
?>
