<?php
include("./connection.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Handle GET requests to fetch pending requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT 
                ar.id AS request_id, 
                ar.request_date AS request_datetime, 
                ar.comment, 
                ar.status, 
                ehr.customer_id, 
                c.first_name, 
                c.last_name, 
                p.name AS product_name 
            FROM 
                approval_requests ar
            LEFT JOIN 
                electronic_health_records ehr ON ar.ehr_id = ehr.id
            LEFT JOIN 
                customers c ON ehr.customer_id = c.id
            LEFT JOIN 
                products p ON ar.product_id = p.id
            WHERE 
                ar.status = 'pending'
            ORDER BY ar.request_date DESC";

    $result = $conn->query($sql);

    // Collect all pending requests
    $requests = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($requests);
    exit;
}

// Handle POST requests to update request status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($input['request_id'], $input['action']) || !in_array($input['action'], ['approve', 'reject'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid input or action.']);
        exit;
    }

    $requestId = intval($input['request_id']);
    $action = $input['action'];
    $comment = isset($input['comment']) ? $input['comment'] : null;

    // Determine the status to set
    $status = $action === 'approve' ? 'approved' : 'rejected';

    // Prepare SQL query to update the request
    $sql = "UPDATE approval_requests 
            SET status = ?, pharmacist_comment = ?, processed_date = NOW() 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the statement.']);
        exit;
    }
    $stmt->bind_param('ssi', $status, $comment, $requestId);

    // Execute the query and handle the response
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Request {$status} successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update request.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Handle invalid request methods
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
exit;
