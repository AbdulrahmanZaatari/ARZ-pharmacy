<?php
include('./connection.php');
header('Content-Type: application/json');

$requestData = json_decode(file_get_contents('php://input'), true);
$request_id = $requestData['request_id'] ?? null;

if ($request_id) {
    $stmt = $conn->prepare("SELECT * FROM pharmacist_verification_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        try {
            $conn->begin_transaction();

            // Include `birth_date` in the insertion query
            $insertStmt = $conn->prepare("
                INSERT INTO pharmacists (first_name, last_name, email, phone_number, address, license_number, degree, certifications, profile_picture, password, birth_date, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())
            ");
            $insertStmt->bind_param(
                "sssssssssss",
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['phone_number'],
                $data['address'],
                $data['license_number'],
                $data['degree'],
                $data['certifications'],
                $data['profile_picture'],
                $data['password'],
                $data['birth_date'] // Include birth_date
            );

            if (!$insertStmt->execute()) {
                throw new Exception("Failed to insert pharmacist: " . $insertStmt->error);
            }

            $deleteStmt = $conn->prepare("DELETE FROM pharmacist_verification_requests WHERE id = ?");
            $deleteStmt->bind_param("i", $request_id);
            if (!$deleteStmt->execute()) {
                throw new Exception("Failed to delete verification request: " . $deleteStmt->error);
            }

            $conn->commit();

            $to = $data['email'];
            $subject = "Verification Approved";
            $message = "Dear {$data['first_name']} {$data['last_name']},\n\nYour verification request has been approved.\n\nWelcome aboard!";
            $headers = "From: no-reply@pharmacy.com";

            mail($to, $subject, $message, $headers);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Request not found.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request ID.']);
}
?>
