<?php
session_start();
include("./connection.php");

// Ensure the user is logged in as the owner
if (!isset($_SESSION['owner_email'])) {
    header("Location: ownerLogin.php");
    exit();
}

// Check if the pharmacist ID is provided
if (isset($_POST['pharmacist_id'])) {
    $pharmacist_id = intval($_POST['pharmacist_id']);

    // Update the pharmacist's status to 'blocked'
    $sql = "UPDATE pharmacists SET status = 'blocked' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pharmacist_id);

    if ($stmt->execute()) {
        // Redirect back to the owner account page with a success message
        $_SESSION['success_message'] = "Pharmacist successfully blocked.";
        header("Location: owner_account.php");
        exit();
    } else {
        // Redirect back to the owner account page with an error message
        $_SESSION['error_message'] = "Failed to block the pharmacist. Please try again.";
        header("Location: owner_account.php");
        exit();
    }
} else {
    // Redirect back to the owner account page if no pharmacist ID is provided
    $_SESSION['error_message'] = "No pharmacist ID provided.";
    header("Location: owner_account.php");
    exit();
}
?>
