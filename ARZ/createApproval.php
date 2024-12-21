<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include("./connection.php");
session_start();

// Debugging: Check incoming POST/GET data

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['PID'])) {
    $PID = $_POST['PID']; // Product ID from POST request
    $UID = $_SESSION['user_id']; // Customer/User ID from session

    // Fetch the EHR (Electronic Health Record) ID for the logged-in customer
    $ehrQuery = "SELECT id FROM electronic_health_records WHERE customer_id='$UID' LIMIT 1";
    $ehrResult = mysqli_query($conn, $ehrQuery);

    // Debugging: Check the result of EHR query
    if (!$ehrResult) {
        echo "EHR Query Error: " . mysqli_error($conn) . "<br>";
        exit();
    }

    $ehr = mysqli_fetch_assoc($ehrResult);
    if (!$ehr) {
        // Handle the case where no EHR is found for the customer
        echo "<script>alert('No health records found. Approval cannot be requested.');</script>";
        header("refresh:1,url=product-details.php?PID=$PID");
        exit();
    }

    $ehr_id = $ehr['id']; // Get the EHR ID

    // Check if there's already a pending approval request for this product and customer
    $checkRequestQuery = "SELECT * FROM approval_requests WHERE customer_id='$UID' AND product_id='$PID' AND status='pending'";
    $checkRequestResult = mysqli_query($conn, $checkRequestQuery);

    // Debugging: Check the result of the check request query
    if (!$checkRequestResult) {
        echo "Check Request Query Error: " . mysqli_error($conn) . "<br>";
        exit();
    }

    if (mysqli_num_rows($checkRequestResult) > 0) {
        // If a pending approval request already exists
        echo "<script>
            alert('An approval request for this product is already pending.');
        </script>";
        header("refresh:1,url=product-details.php?PID=$PID");
        exit();
    }

    // Insert the approval request into the `approval_requests` table
    $approvalRequest = "INSERT INTO approval_requests (customer_id, product_id, request_date, status, ehr_id) 
                        VALUES ('$UID', '$PID', NOW(), 'pending', '$ehr_id')";
    if (mysqli_query($conn, $approvalRequest)) {
        // Debugging: Log success message
        echo "Approval request inserted successfully.<br>";

        // Success message after inserting the request
        echo "<script>
            alert('Approval request sent successfully.');    
        </script>";
    } else {
        // Debugging: Log error if the query fails
        echo "Approval Request Insert Error: " . mysqli_error($conn) . "<br>";
        echo "<script>
            alert('Failed to send approval request. Please try again.');    
        </script>";
    }

    // Redirect back to the product details page
    header("refresh:2,url=product-details.php?PID=$PID");
} else {
    echo "Invalid request.";
    exit();
}
?>
