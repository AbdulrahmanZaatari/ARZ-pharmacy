<?php
session_start();
include("./connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id = $_SESSION['owner_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            // Update profile picture in the database
            $stmt = $conn->prepare("UPDATE owners SET profile_picture = ? WHERE id = ?");
            $stmt->bind_param("si", $file_name, $owner_id);
            $stmt->execute();
        }
    }

    // Update name and email
    $stmt = $conn->prepare("UPDATE owners SET first_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $owner_id);
    $stmt->execute();

    header("Location: owner_account.php");
    exit();
}
?>
