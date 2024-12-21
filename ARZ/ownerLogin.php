<?php
session_start();
ob_start(); // Start output buffering
include("./connection.php");
include("./role_based_header.php"); // Include the header

// Initialize error message
$error_message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if inputs are not empty
    if (empty($email) || empty($password)) {
        $error_message = "Both email and password are required.";
    } else {
        // Fetch owner details from the database
        $sql = "SELECT * FROM owners WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the owner exists
        if ($result->num_rows === 1) {
            $owner = $result->fetch_assoc();

            // Compare the plain text password (use `password_verify` if passwords are hashed)
            if ($password === $owner['password']) {
                // Set session variables
                $_SESSION['owner_email'] = $owner['email'];
                $_SESSION['owner_id'] = $owner['id'];
                $_SESSION['role'] = 'owner'; // Set the role as 'owner'

                // Redirect to the owner account page
                header("Location: owner_account.php");
                exit();
            } else {
                $error_message = "Incorrect password. Please try again.";
            }
        } else {
            $error_message = "No owner account found with the given email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link your CSS file -->
</head>
<body>
    <div class="login-container" style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background: #f9f9f9;">
        <h1 style="text-align: center; font-size: 24px; margin-bottom: 20px;">Owner Login</h1>

        <!-- Display Error Message -->
        <?php if (!empty($error_message)): ?>
            <div style="color: red; margin-bottom: 15px; text-align: center;"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="ownerLogin.php" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="email" style="display: block; margin-bottom: 5px;">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" id="password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Login</button>
        </form>
    </div>
    <?php include("./footer.php"); ?> <!-- Include the footer -->
</body>
</html>
