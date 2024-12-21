<?php
session_start();
include("./connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pharmacist_id = $_SESSION['pharmacist_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            // Update profile picture in the database
            $stmt = $conn->prepare("UPDATE pharmacists SET profile_picture = ? WHERE id = ?");
            $stmt->bind_param("si", $file_name, $pharmacist_id);
            $stmt->execute();
        }
    }

    // Update name and email
    $stmt = $conn->prepare("UPDATE pharmacists SET first_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $pharmacist_id);
    $stmt->execute();

    header("Location: pharmacist_account.php");
    exit();
}
?>
