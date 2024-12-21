<?php
include('./connection.php');
header('Content-Type: application/json');

$requestData = json_decode(file_get_contents('php://input'), true);
$request_id = $requestData['request_id'] ?? null;

if ($request_id) {
    $stmt = $conn->prepare("SELECT first_name, last_name, email, license_number, profile_picture, request_date, degree, certifications FROM pharmacist_verification_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Construct full path for profile picture
        $profile_picture_path = !empty($data['profile_picture']) ? $data['profile_picture'] : "uploads/default_profile.png";

        echo json_encode([
            'success' => true,
            'profile_picture' => $profile_picture_path,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'license_number' => $data['license_number'],
            'request_date' => $data['request_date'],
            'degree' => $data['degree'],
            'certifications' => $data['certifications']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
