<?php
session_start();
include("./connection.php");
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step'])) {
        $step = $_POST['step'];

        if ($step === 'verify_email') {
            // Step 1: Verify the email
            $email = $conn->real_escape_string($_POST['email']);
            $query = "SELECT id, question1_answer, question2_answer FROM customers WHERE email = '$email'";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['reset_user_id'] = $user['id'];
                $_SESSION['question1_answer'] = $user['question1_answer'];
                $_SESSION['question2_answer'] = $user['question2_answer'];
                $_SESSION['reset_email'] = $email;
                $step = 'verify_questions';
            } else {
                $error = "Email not found!";
            }
        } elseif ($step === 'verify_questions') {
            // Step 2: Verify the security questions
            $answer1 = strtolower(trim($_POST['question1_answer']));
            $answer2 = strtolower(trim($_POST['question2_answer']));

            if (
                $answer1 === strtolower($_SESSION['question1_answer']) &&
                $answer2 === strtolower($_SESSION['question2_answer'])
            ) {
                $step = 'reset_password';
            } else {
                $error = "Security answers are incorrect!";
            }
        } elseif ($step === 'reset_password') {
            // Step 3: Reset the password
            $new_password = $conn->real_escape_string($_POST['new_password']);
            $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $user_id = $_SESSION['reset_user_id'];
                $update_query = "UPDATE customers SET password = '$hashed_password' WHERE id = $user_id";

                if ($conn->query($update_query)) {
                    echo "<script>alert('Password reset successfully!'); window.location.href='login.php';</script>";
                    session_destroy();
                    exit();
                } else {
                    $error = "Failed to reset password!";
                }
            } else {
                $error = "Passwords do not match!";
            }
        }
    }
} else {
    // Initial step
    $step = 'verify_email';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #333333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #007bff;
        }
        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
        }
        .alert-danger {
            background-color: #dc3545;
        }
        .form-title {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            color: #555555;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Forgot Password</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($step === 'verify_email'): ?>
        <!-- Step 1: Verify Email -->
        <div class="form-title">Enter your email to start the recovery process</div>
        <form method="POST">
            <input type="hidden" name="step" value="verify_email">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" placeholder="Enter your registered email" required>
            </div>
            <button type="submit">Next</button>
        </form>
    <?php elseif ($step === 'verify_questions'): ?>
        <!-- Step 2: Verify Security Questions -->
        <div class="form-title">Answer your security questions</div>
        <form method="POST">
            <input type="hidden" name="step" value="verify_questions">
            <div class="form-group">
                <label for="question1">What's the name of your grandma?</label>
                <input type="text" id="question1" name="question1_answer" placeholder="Enter your answer" required>
            </div>
            <div class="form-group">
                <label for="question2">What's the name of your pet?</label>
                <input type="text" id="question2" name="question2_answer" placeholder="Enter your answer" required>
            </div>
            <button type="submit">Next</button>
        </form>
    <?php elseif ($step === 'reset_password'): ?>
        <!-- Step 3: Reset Password -->
        <div class="form-title">Set a new password</div>
        <form method="POST">
            <input type="hidden" name="step" value="reset_password">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" placeholder="Enter a strong password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter the password" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
