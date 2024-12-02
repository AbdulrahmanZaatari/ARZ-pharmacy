<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database Connection
include("./connection.php");

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Extract customer data
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $birth_date = $conn->real_escape_string($_POST['birth_date']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone_number = $conn->real_escape_string($_POST['phone_number']);
        $address = $conn->real_escape_string($_POST['address']);
        $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
        $security_question1 = $conn->real_escape_string($_POST['security_question1']);
        $security_question2 = $conn->real_escape_string($_POST['security_question2']);

        // Check for duplicate email
        $emailCheckSQL = "SELECT id FROM customers WHERE email = ?";
        $emailCheckStmt = $conn->prepare($emailCheckSQL);
        $emailCheckStmt->bind_param("s", $email);
        $emailCheckStmt->execute();
        $emailCheckStmt->store_result();

        if ($emailCheckStmt->num_rows > 0) {
            die(json_encode(['success' => false, 'message' => 'The email address is already in use.']));
        }

        // Insert customer
        $insertCustomerSQL = "INSERT INTO customers 
                              (first_name, last_name, birth_date, email, phone_number, address, password, question1_answer, question2_answer, register, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'incomplete', NOW())";

        $stmt = $conn->prepare($insertCustomerSQL);
        $stmt->bind_param(
            "sssssssss",
            $firstname,
            $lastname,
            $birth_date,
            $email,
            $phone_number,
            $address,
            $password,
            $security_question1,
            $security_question2
        );

        if (!$stmt->execute()) {
            throw new Exception("Error inserting customer: " . $stmt->error);
        }

        $customerId = $conn->insert_id; // Get the auto-incremented ID
        error_log("Customer created with ID: $customerId");

        // Redirect to the EHR form
        header("Location: ehr_form.php?customer_id=" . $customerId);
        exit;

    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        die(json_encode(['success' => false, 'message' => $e->getMessage()]));
    }
}
