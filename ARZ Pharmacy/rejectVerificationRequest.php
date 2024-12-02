<?php
include('./connection.php');
header('Content-Type: application/json');

$requestData = json_decode(file_get_contents('php://input'), true);
$request_id = $requestData['request_id'] ?? null;

if ($request_id) {
    // Fetch verification request details
    $stmt = $conn->prepare("SELECT * FROM pharmacist_verification_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        try {
            // Begin Transaction
            $conn->begin_transaction();

            // Delete the verification request
            $deleteStmt = $conn->prepare("DELETE FROM pharmacist_verification_requests WHERE id = ?");
            $deleteStmt->bind_param("i", $request_id);
            if (!$deleteStmt->execute()) {
                throw new Exception("Failed to delete verification request: " . $deleteStmt->error);
            }

            // Commit transaction
            $conn->commit();

            // Send email notification
            $to = $data['email'];
            $subject = "Pharmacist Verification Rejected";
            $message = "Dear " . $data['first_name'] . " " . $data['last_name'] . ",\n\nWe regret to inform you that your pharmacist verification request has been rejected.\n\nThank you for your interest.";
            $headers = "From: admin@pharmacy.com";
            mail($to, $subject, $message, $headers);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Request not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request ID']);
}
?>
