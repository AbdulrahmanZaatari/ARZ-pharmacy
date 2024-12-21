<?php
session_start();
include("./connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all questions, ensuring pinned questions appear at the top
$sql_questions = "SELECT id, subject, message, pinned, created_at FROM questions ORDER BY pinned DESC, created_at DESC";
$result_questions = $conn->query($sql_questions);

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

include("./role_based_header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q&A</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .qa-container {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }

        .qa-container:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .qa-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .qa-message {
            font-size: 14px;
            color: #555;
            margin: 10px 0;
        }

        .qa-meta {
            font-size: 12px;
            color: #999;
        }

        .qa-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .qa-buttons a, .qa-buttons button {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            border: none;
            cursor: pointer;
        }

        .qa-buttons a:hover, .qa-buttons button:hover {
            background-color: #0056b3;
        }

        .qa-pin {
            color: gold;
            font-weight: bold;
        }

        .ask-question-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
        }

        .ask-question-btn:hover {
            background-color: #218838;
        }

        .ask-question-container {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if ($role == null): ?>
        <div class="ask-question-container">
            <a href="sendMessage.php" class="ask-question-btn">Have a question?</a>
        </div>
    <?php endif; ?>

    <h1 style="text-align: center; margin: 20px 0;">Questions & Answers</h1>
    <?php if ($result_questions->num_rows > 0): ?>
        <?php while ($question = $result_questions->fetch_assoc()): ?>
            <div class="qa-container">
                <div>
                    <span class="qa-title"><?php echo htmlspecialchars($question['subject']); ?></span>
                    <?php if ($question['pinned']): ?>
                        <span class="qa-pin">Pinned</span>
                    <?php endif; ?>
                </div>
                <div class="qa-message"><?php echo nl2br(htmlspecialchars($question['message'])); ?></div>
                <div class="qa-meta">Posted on: <?php echo date("F d, Y", strtotime($question['created_at'])); ?></div>
                <div class="qa-buttons">
                    <?php if ($role === 'pharmacist'): ?>
                        <button onclick="deleteQuestion(<?php echo $question['id']; ?>)">Delete Question</button>
                        <a href="view_answer.php?question_id=<?php echo $question['id']; ?>">View Answers</a>
                    <?php elseif ($role === 'customer' || $role === 'owner'): ?>
                        <a href="view_answer.php?question_id=<?php echo $question['id']; ?>">View Answers</a>
                    <?php else: ?>
                        <a href="view_answer.php?question_id=<?php echo $question['id']; ?>">View Answers</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No questions available.</p>
    <?php endif; ?>
</div>

<script>
    function deleteQuestion(questionId) {
        if (confirm("Are you sure you want to delete this question?")) {
            fetch('delete_question.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ question_id: questionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Question deleted successfully");
                    location.reload();
                } else {
                    alert("Failed to delete question: " + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
</body>
</html>

<?php
$conn->close();
include("./pharmacist_footer.php");
?>
