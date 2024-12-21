<?php
require('fpdf186/fpdf.php');
include('connection.php');

// Check if customer_id is provided
if (!isset($_GET['customer_id'])) {
    die("Error: Customer ID not provided.");
}

$customer_id = intval($_GET['customer_id']);

// Fetch customer and health record details
$sql = "SELECT 
            c.first_name, 
            c.last_name, 
            ehr.chronic_conditions, 
            ehr.medications, 
            ehr.allergies, 
            ehr.family_history, 
            ehr.current_height, 
            ehr.current_weight, 
            ehr.tobacco_use, 
            ehr.tobacco_details, 
            ehr.alcohol_consumption, 
            ehr.alcohol_details, 
            ehr.physical_exercise, 
            ehr.sitting_hours, 
            ehr.diet_description, 
            ehr.dietary_restrictions, 
            ehr.sleep_hours, 
            ehr.sleep_concerns, 
            ehr.stress_level, 
            ehr.mental_health, 
            ehr.additional_notes, 
            ehr.vaccination_status, 
            ehr.flu_shot, 
            ehr.health_insurance, 
            ehr.insurance_provider_name, 
            ehr.policy_number, 
            ehr.group_number, 
            ehr.emergency_contact_name, 
            ehr.emergency_contact_relationship, 
            ehr.emergency_contact_phone 
        FROM customers c
        JOIN electronic_health_records ehr ON c.id = ehr.customer_id
        WHERE ehr.customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: No records found for the given Customer ID.");
}

$row = $result->fetch_assoc();

// Generate the PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add Title
$pdf->SetTextColor(0, 102, 204); // Blue color for the title
$pdf->Cell(0, 10, 'Electronic Health Record', 0, 1, 'C');
$pdf->Ln(10);

// Add Section: Customer Information
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 10, 'Patient Information', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Name: ' . $row['first_name'] . ' ' . $row['last_name'], 0, 1);
$pdf->Ln(10);

// Add Section: Health Record
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Health Record Details', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);

// Add health details with explanations
$pdf->Cell(0, 10, 'Chronic Conditions: ' . ($row['chronic_conditions'] ?: 'None') . ' (e.g., Diabetes, Hypertension)', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Medications: ' . ($row['medications'] ?: 'None') . ' (e.g., Aspirin 75mg daily)', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Allergies: ' . ($row['allergies'] ?: 'None') . ' (e.g., Penicillin, Peanuts)', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Family History: ' . ($row['family_history'] ?: 'No known conditions') . ' (e.g., Mother - Hypertension)', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Current Height: ' . ($row['current_height'] ?: 'Not provided') . ' cm', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Current Weight: ' . ($row['current_weight'] ?: 'Not provided') . ' kg', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Tobacco Use: ' . ($row['tobacco_use'] ?: 'No') . ' (e.g., Smokes 5 cigarettes/day)', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Tobacco Details: ' . ($row['tobacco_details'] ?: 'No details provided'), 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Alcohol Consumption: ' . ($row['alcohol_consumption'] ?: 'No') . ' (e.g., 2 glasses of wine/week)', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Alcohol Details: ' . ($row['alcohol_details'] ?: 'No details provided'), 0, 1);
$pdf->Ln(10);

// Add Section: Lifestyle Details
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Lifestyle Details', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Physical Exercise: ' . ($row['physical_exercise'] ?: 'No') . ' (e.g., Runs 3 times/week)', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Sitting Hours: ' . ($row['sitting_hours'] ?: 'Not provided') . ' hours/day', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Diet Description: ' . ($row['diet_description'] ?: 'No details provided'), 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Dietary Restrictions: ' . ($row['dietary_restrictions'] ?: 'No restrictions'), 0, 1);
$pdf->Ln(10);

// Add Section: Sleep and Stress
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Sleep and Stress Details', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Sleep Hours: ' . ($row['sleep_hours'] ?: 'Not provided') . ' hours/night', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Sleep Concerns: ' . ($row['sleep_concerns'] ?: 'No concerns'), 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Stress Level: ' . ($row['stress_level'] ?: 'Not provided') . ' (e.g., High, Moderate, Low)', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Mental Health: ' . ($row['mental_health'] ?: 'No issues'), 0, 1);
$pdf->Ln(10);

// Add Section: Emergency Contact
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Emergency Contact', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Name: ' . ($row['emergency_contact_name'] ?: 'Not provided'), 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Relationship: ' . ($row['emergency_contact_relationship'] ?: 'Not provided'), 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Phone: ' . ($row['emergency_contact_phone'] ?: 'Not provided'), 0, 1);

// Add a footer
$pdf->SetY(-30);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Generated on ' . date('Y-m-d') . ' by ARZ Pharmacy', 0, 0, 'C');

// Output PDF to download
$pdf->Output('D', 'EHR_Customer_' . $customer_id . '.pdf');
?>
