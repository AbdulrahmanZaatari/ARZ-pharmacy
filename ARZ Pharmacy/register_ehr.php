<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("./connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction(); // Start a transaction

    try {
        // Retrieve and sanitize the customer ID
        $customer_id = $conn->real_escape_string($_POST['customer_id']);

        // Validate the customer ID
        if (!$customer_id || !is_numeric($customer_id)) {
            throw new Exception("Invalid or missing customer ID.");
        }

        // Extract and sanitize EHR data from the form
        $chronic_conditions = $conn->real_escape_string($_POST['chronic_conditions'] ?? 'No');
        $allergies = $conn->real_escape_string($_POST['allergies'] ?? 'No');
        $current_height = $conn->real_escape_string($_POST['current_height']);
        $current_weight = $conn->real_escape_string($_POST['current_weight']);
        $family_history = $conn->real_escape_string($_POST['family_history'] ?? '');
        $medications = $conn->real_escape_string($_POST['medications'] ?? '');
        $tobacco_use = $conn->real_escape_string($_POST['tobacco_use'] ?? 'No');
        $diet_description = $conn->real_escape_string($_POST['diet_description'] ?? '');
        $sleep_hours = $conn->real_escape_string($_POST['sleep_hours'] ?? '');
        $vaccination_status = $conn->real_escape_string($_POST['vaccination_status'] ?? 'No');
        $insurance_provider_name = $conn->real_escape_string($_POST['insurance_provider_name'] ?? '');
        $policy_number = $conn->real_escape_string($_POST['policy_number'] ?? '');
        $group_number = $conn->real_escape_string($_POST['group_number'] ?? '');
        $emergency_contact_name = $conn->real_escape_string($_POST['emergency_contact_name'] ?? '');
        $emergency_contact_relationship = $conn->real_escape_string($_POST['emergency_contact_relationship'] ?? '');
        $emergency_contact_phone = $conn->real_escape_string($_POST['emergency_contact_phone'] ?? '');

        // Insert EHR data into the database
        $ehrSQL = "INSERT INTO electronic_health_records 
                   (customer_id, chronic_conditions, allergies, current_height, current_weight, family_history, medications, tobacco_use, diet_description, sleep_hours, vaccination_status, insurance_provider_name, policy_number, group_number, emergency_contact_name, emergency_contact_relationship, emergency_contact_phone, created_at, updated_at) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $ehrStmt = $conn->prepare($ehrSQL);
        if (!$ehrStmt) {
            throw new Exception("Failed to prepare the EHR insertion query.");
        }

        $ehrStmt->bind_param(
            "isssssssissssssss",
            $customer_id,
            $chronic_conditions,
            $allergies,
            $current_height,
            $current_weight,
            $family_history,
            $medications,
            $tobacco_use,
            $diet_description,
            $sleep_hours,
            $vaccination_status,
            $insurance_provider_name,
            $policy_number,
            $group_number,
            $emergency_contact_name,
            $emergency_contact_relationship,
            $emergency_contact_phone
        );

        if (!$ehrStmt->execute()) {
            throw new Exception("Error inserting EHR: " . $ehrStmt->error);
        }

        // Update the customer status to 'complete'
        $updateStatusSQL = "UPDATE customers SET register = 'complete' WHERE id = ?";
        $updateStatusStmt = $conn->prepare($updateStatusSQL);
        $updateStatusStmt->bind_param("i", $customer_id);

        if (!$updateStatusStmt->execute()) {
            throw new Exception("Failed to update customer status: " . $updateStatusStmt->error);
        }

        // Commit the transaction
        $conn->commit();

        // Automatically log the user in after successful registration
        session_start();
        $_SESSION['customer_id'] = $customer_id;

        // Redirect to the account page
        header("Location: account.php");
        exit();

    } catch (Exception $e) {
        // Roll back the transaction in case of an error
        $conn->rollback();
        error_log("Error: " . $e->getMessage());
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>
