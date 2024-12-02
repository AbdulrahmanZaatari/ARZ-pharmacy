<?php
session_start();
include("./connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure session variable is set
    if (!isset($_SESSION['user_id'])) {
        die("Customer ID is not set in the session.");
    }

    $customer_id = $_SESSION['user_id'];
    $first_name = $_POST['first'];
    $last_name = $_POST['last'];
    $email = $_POST['email'];

    // Initialize file name
    $file_name = null;

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;

        // Check if the file upload was successful
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            // Update profile picture in the database
            $stmt = $conn->prepare("UPDATE customers SET profile_picture = ? WHERE id = ?");
            $stmt->bind_param("si", $file_name, $customer_id);

            // Execute and check for errors
            if (!$stmt->execute()) {
                die("Database Error (Profile Picture): " . $stmt->error);
            }
        } else {
            die("File upload failed. Check permissions for the uploads/ directory.");
        }
    }

    // Update name and email
    $stmt = $conn->prepare("UPDATE customers SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $first_name,$last_name, $email, $customer_id);

    // Execute and check for errors
    if (!$stmt->execute()) {
        die("Database Error (Name/Email): " . $stmt->error);
    }

    // Redirect after update
    header("Location: account.php");
    exit();
}
?>
