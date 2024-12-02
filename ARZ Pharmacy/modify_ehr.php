<?php
session_start();
include("./connection.php");
include("./role_based_header.php");

// Get the customer's ID
$customer_id = $_SESSION['user_id'];

// Fetch existing EHR data
$ehrSQL = "SELECT * FROM electronic_health_records WHERE customer_id = ?";
$stmt = $conn->prepare($ehrSQL);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$ehr = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $chronic_conditions = $conn->real_escape_string($_POST['chronic_conditions']);
    $allergies = $conn->real_escape_string($_POST['allergies']);
    $current_height = $conn->real_escape_string($_POST['current_height']);
    $current_weight = $conn->real_escape_string($_POST['current_weight']);
    $family_history = $conn->real_escape_string($_POST['family_history']);
    $medications = $conn->real_escape_string($_POST['medications']);
    $tobacco_use = $conn->real_escape_string($_POST['tobacco_use']);
    $diet_description = $conn->real_escape_string($_POST['diet_description']);
    $sleep_hours = $conn->real_escape_string($_POST['sleep_hours']);
    $vaccination_status = $conn->real_escape_string($_POST['vaccination_status']);
    $insurance_provider_name = $conn->real_escape_string($_POST['insurance_provider_name']);
    $policy_number = $conn->real_escape_string($_POST['policy_number']);
    $group_number = $conn->real_escape_string($_POST['group_number']);
    $emergency_contact_name = $conn->real_escape_string($_POST['emergency_contact_name']);
    $emergency_contact_relationship = $conn->real_escape_string($_POST['emergency_contact_relationship']);
    $emergency_contact_phone = $conn->real_escape_string($_POST['emergency_contact_phone']);

    // Update the EHR data
    $updateSQL = "UPDATE electronic_health_records SET 
                  chronic_conditions = ?, allergies = ?, current_height = ?, current_weight = ?, family_history = ?, 
                  medications = ?, tobacco_use = ?, diet_description = ?, sleep_hours = ?, vaccination_status = ?, 
                  insurance_provider_name = ?, policy_number = ?, group_number = ?, emergency_contact_name = ?, 
                  emergency_contact_relationship = ?, emergency_contact_phone = ?, updated_at = NOW() 
                  WHERE customer_id = ?";
    $updateStmt = $conn->prepare($updateSQL);
    $updateStmt->bind_param(
        "ssssssssssssssssi",
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
        $emergency_contact_phone,
        $customer_id
    );

    if ($updateStmt->execute()) {
        echo "<script>alert('EHR updated successfully!'); window.location.href = 'account.php';</script>";
    } else {
        echo "<script>alert('Error updating EHR. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify EHR</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file -->
</head>
<body>
<div class="container">
    <h2>Modify Your Electronic Health Record</h2>
    <form method="POST">
        <div class="form-group">
            <label for="chronic_conditions">Chronic Conditions</label>
            <input type="text" id="chronic_conditions" name="chronic_conditions" value="<?php echo htmlspecialchars($ehr['chronic_conditions'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="allergies">Allergies</label>
            <input type="text" id="allergies" name="allergies" value="<?php echo htmlspecialchars($ehr['allergies'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="current_height">Height (cm)</label>
            <input type="text" id="current_height" name="current_height" value="<?php echo htmlspecialchars($ehr['current_height'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="current_weight">Weight (kg)</label>
            <input type="text" id="current_weight" name="current_weight" value="<?php echo htmlspecialchars($ehr['current_weight'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="family_history">Family History</label>
            <textarea id="family_history" name="family_history"><?php echo htmlspecialchars($ehr['family_history'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="medications">Medications</label>
            <textarea id="medications" name="medications"><?php echo htmlspecialchars($ehr['medications'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="tobacco_use">Tobacco Use</label>
            <input type="text" id="tobacco_use" name="tobacco_use" value="<?php echo htmlspecialchars($ehr['tobacco_use'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="diet_description">Diet Description</label>
            <textarea id="diet_description" name="diet_description"><?php echo htmlspecialchars($ehr['diet_description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="sleep_hours">Sleep Hours</label>
            <input type="text" id="sleep_hours" name="sleep_hours" value="<?php echo htmlspecialchars($ehr['sleep_hours'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="vaccination_status">Vaccination Status</label>
            <input type="text" id="vaccination_status" name="vaccination_status" value="<?php echo htmlspecialchars($ehr['vaccination_status'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="insurance_provider_name">Insurance Provider</label>
            <input type="text" id="insurance_provider_name" name="insurance_provider_name" value="<?php echo htmlspecialchars($ehr['insurance_provider_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="policy_number">Policy Number</label>
            <input type="text" id="policy_number" name="policy_number" value="<?php echo htmlspecialchars($ehr['policy_number'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="group_number">Group Number</label>
            <input type="text" id="group_number" name="group_number" value="<?php echo htmlspecialchars($ehr['group_number'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="emergency_contact_name">Emergency Contact Name</label>
            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?php echo htmlspecialchars($ehr['emergency_contact_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="emergency_contact_relationship">Emergency Contact Relationship</label>
            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="<?php echo htmlspecialchars($ehr['emergency_contact_relationship'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="emergency_contact_phone">Emergency Contact Phone</label>
            <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="<?php echo htmlspecialchars($ehr['emergency_contact_phone'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update EHR</button>
    </form>
</div>
</body>
</html>
