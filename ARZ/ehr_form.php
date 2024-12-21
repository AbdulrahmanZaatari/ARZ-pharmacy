<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("./connection.php");
// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
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

        // Combine all EHR details into a PDF-compatible format
        $ehr_details = "
            Chronic Conditions: {$_POST['chronic_conditions']}
            Chronic Conditions Details: {$_POST['chronic_conditions_details']}
            Allergies: {$_POST['allergies']}
            Allergies Details: {$_POST['allergies_details']}
            Current Height: {$_POST['current_height']}
            Current Weight: {$_POST['current_weight']}
            Family History: {$_POST['family_history']}
            Medications: {$_POST['medications']}
            Supplements: {$_POST['supplements']}
            Tobacco Use: {$_POST['tobacco_use']}
            Tobacco Details: {$_POST['tobacco_details']}
            Alcohol Consumption: {$_POST['alcohol_consumption']}
            Alcohol Details: {$_POST['alcohol_details']}
            Physical Exercise: {$_POST['physical_exercise']}
            Sitting Hours: {$_POST['sitting_hours']}
            Diet Description: {$_POST['diet_description']}
            Dietary Restrictions: {$_POST['dietary_restrictions']}
            Sleep Hours: {$_POST['sleep_hours']}
            Sleep Concerns: " . implode(', ', $_POST['sleep_concerns'] ?? []) . "
            Mental Health Concerns: {$_POST['mental_health_concerns']}
            Mental Health Details: {$_POST['mental_health_details']}
            Additional Notes: {$_POST['additional_notes']}
            Vaccination Status: {$_POST['vaccination_status']}
            Insurance Provider Name: {$_POST['insurance_provider_name']}
            Policy Number: {$_POST['policy_number']}
            Group Number: {$_POST['group_number']}
            Emergency Contact Name: {$_POST['emergency_contact_name']}
            Emergency Contact Relationship: {$_POST['emergency_contact_relationship']}
            Emergency Contact Phone: {$_POST['emergency_contact_phone']}
        ";

        // Save details as a PDF file
        $ehr_pdf_path = "ehr_pdfs/ehr_{$customer_id}_" . time() . ".pdf";
        file_put_contents($ehr_pdf_path, $ehr_details);

        // Insert EHR record into the database
        $ehrSQL = "INSERT INTO electronic_health_record (customer_id, ehr_pdf_path, created_a) 
                   VALUES (?, ?, NOW())";
        $ehrStmt = $conn->prepare($ehrSQL);
        if (!$ehrStmt) {
            throw new Exception("Failed to prepare the EHR insertion query.");
        }

        $ehrStmt->bind_param("is", $customer_id, $ehr_pdf_path);

        if (!$ehrStmt->execute()) {
            throw new Exception("Error inserting EHR: " . $ehrStmt->error);
        }

        // Commit the transaction
        $conn->commit();

        // Display success message
        echo "<script>alert('EHR successfully created!'); window.location.href = 'account.php';</script>";

    } catch (Exception $e) {
        // Roll back the transaction in case of an error
        $conn->rollback();
        error_log("Error: " . $e->getMessage());
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>


<!-- EHR FORM -->
<?php include("./header.php"); ?>

<form action="register_ehr.php" method="POST" class="ltn__form-box contact-form-box">
                        <h3>Patient Information Questionnaire</h3>
                        
                        <!-- Pass Customer ID -->
                        <input type="hidden" name="customer_id" value="<?php echo $_GET['customer_id']; ?>">
                    
                        <!-- General Health and Medical History -->
                        <h4>1. General Health and Medical History</h4>
                        <div class="mb-3">
                            <label>Do you have any chronic health conditions? (e.g., cancer, diabetes, hypertension, asthma)</label><br>
                            <input type="radio" name="chronic_conditions" value="yes" required> Yes
                            <input type="radio" name="chronic_conditions" value="no" required> No
                            <input type="text" name="chronic_conditions_details" placeholder="If yes, please list:" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Do you have any known allergies?</label><br>
                            <input type="radio" name="allergies" value="yes" required> Yes
                            <input type="radio" name="allergies" value="no" required> No
                            <input type="text" name="allergies_details" placeholder="If yes, please specify:" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>What is your current height?</label>
                            <input type="text" name="current_height" placeholder="cm or ft/in" required>
                        </div>
                        <div class="mb-3">
                            <label>What is your current weight?</label>
                            <input type="text" name="current_weight" placeholder="kg or lbs" required>
                        </div>
                        <div class="mb-3">
                            <label>Do you have any family members with chronic health conditions?</label><br>
                            <input type="radio" name="family_conditions" value="yes" required> Yes
                            <input type="radio" name="family_conditions" value="no" required> No
                            <input type="text" name="family_history" placeholder="If yes, please specify:" style="margin-top: 10px; width: 100%;">
                        </div>
                    
                        <!-- Medication and Supplement Use -->
                        <h4>2. Medication and Supplement Use</h4>
                        <div class="mb-3">
                            <label>Are you currently taking any medications?</label><br>
                            <input type="radio" name="current_medications" value="yes" required> Yes
                            <input type="radio" name="current_medications" value="no" required> No
                            <input type="text" name="medications" placeholder="If yes, please list:" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Do you take any over-the-counter medications, vitamins, or supplements?</label><br>
                            <input type="radio" name="supplements" value="yes" required> Yes
                            <input type="radio" name="supplements" value="no" required> No
                            <input type="text" name="supplements" placeholder="If yes, please specify:" style="margin-top: 10px; width: 100%;">
                        </div>
                    
                        <!-- Lifestyle Information -->
                        <h4>3. Lifestyle and Additional Information</h4>
                        <div class="mb-3">
                            <label>Do you use tobacco products?</label><br>
                            <input type="radio" name="tobacco_use" value="yes" required> Yes
                            <input type="radio" name="tobacco_use" value="no" required> No
                            <input type="text" name="tobacco_details" placeholder="If yes, how many per day?" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Do you consume alcohol?</label><br>
                            <input type="radio" name="alcohol_consumption" value="yes" required> Yes
                            <input type="radio" name="alcohol_consumption" value="no" required> No
                            <input type="text" name="alcohol_details" placeholder="If yes, how many drinks per week?" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>How often do you engage in physical exercise?</label><br>
                            <input type="radio" name="physical_exercise" value="daily" required> Daily
                            <input type="radio" name="physical_exercise" value="3-4 times per week" required> 3-4 times per week
                            <input type="radio" name="physical_exercise" value="1-2 times per week" required> 1-2 times per week
                            <input type="radio" name="physical_exercise" value="rarely" required> Rarely
                            <input type="radio" name="physical_exercise" value="never" required> Never
                        </div>
                        <div class="mb-3">
                            <label>On a typical day, how many hours do you spend sitting?</label><br>
                            <input type="radio" name="sitting_hours" value="less than 2 hours" required> Less than 2 hours
                            <input type="radio" name="sitting_hours" value="2-4 hours" required> 2-4 hours
                            <input type="radio" name="sitting_hours" value="4-6 hours" required> 4-6 hours
                            <input type="radio" name="sitting_hours" value="more than 6 hours" required> More than 6 hours
                        </div>
                        <div class="mb-3">
                            <label>How would you describe your diet?</label><br>
                            <input type="radio" name="diet_description" value="balanced" required> Balanced
                            <input type="radio" name="diet_description" value="high in carbohydrates" required> High in carbohydrates
                            <input type="radio" name="diet_description" value="high in protein" required> High in protein
                            <input type="radio" name="diet_description" value="vegetarian/vegan" required> Vegetarian/Vegan
                            <input type="radio" name="diet_description" value="low-calorie/low-carb" required> Low-calorie/Low-carb
                            <input type="radio" name="diet_description" value="other" required> Other
                            <input type="text" name="dietary_restrictions" placeholder="Other (please specify):" style="margin-top: 10px; width: 100%;">
                        </div>
                    
                        <!-- Sleep and Mental Health -->
                        <h4>4. Sleep and Mental Health</h4>
                        <div class="mb-3">
                            <label>How many hours of sleep do you get on average per night?</label><br>
                            <input type="radio" name="sleep_hours" value="less than 5 hours" required> Less than 5 hours
                            <input type="radio" name="sleep_hours" value="5-6 hours" required> 5-6 hours
                            <input type="radio" name="sleep_hours" value="6-7 hours" required> 6-7 hours
                            <input type="radio" name="sleep_hours" value="7-8 hours" required> 7-8 hours
                            <input type="radio" name="sleep_hours" value="more than 8 hours" required> More than 8 hours
                        </div>
                        <div class="mb-3">
                            <label>Do you have any sleep-related concerns?</label><br>
                            <input type="checkbox" name="sleep_concerns[]" value="difficulty_falling_asleep"> Difficulty falling asleep<br>
                            <input type="checkbox" name="sleep_concerns[]" value="waking_up_frequently"> Waking up frequently<br>
                            <input type="checkbox" name="sleep_concerns[]" value="feeling_tired_upon_waking"> Feeling tired upon waking up<br>
                            <input type="text" name="sleep_concerns_other" placeholder="Other (please specify):" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Do you have any mental health concerns or conditions? (e.g., anxiety, depression)</label><br>
                            <input type="radio" name="mental_health_concerns" value="yes" required> Yes
                            <input type="radio" name="mental_health_concerns" value="no" required> No
                            <input type="text" name="mental_health_details" placeholder="If yes, please specify:" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Is there anything else you would like the pharmacist to know?</label>
                            <textarea name="additional_notes" rows="3" placeholder="Please specify..." style="width: 100%;"></textarea>
                        </div>
                    
                        <!-- Immunization and Insurance -->
                        <h4>5. Immunization and Insurance</h4>
                        <div class="mb-3">
                            <label>Are your vaccinations up to date?</label><br>
                            <input type="radio" name="vaccination_status" value="yes" required> Yes
                            <input type="radio" name="vaccination_status" value="no" required> No
                        </div>
                        <div class="mb-3">
                            <label>Insurance Provider Name:</label>
                            <input type="text" name="insurance_provider_name" placeholder="Insurance Provider Name" style="width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Policy Number:</label>
                            <input type="text" name="policy_number" placeholder="Policy Number" style="width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Group Number:</label>
                            <input type="text" name="group_number" placeholder="Group Number" style="width: 100%;">
                        </div>
                    
                        <!-- Emergency Contact -->
                        <h4>6. Emergency Contact Information</h4>
                        <div class="mb-3">
                            <label>Name:</label>
                            <input type="text" name="emergency_contact_name" placeholder="Emergency Contact Name" style="width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Relationship:</label>
                            <input type="text" name="emergency_contact_relationship" placeholder="Relationship" style="width: 100%;">
                        </div>
                        <div class="mb-3">
                            <label>Phone Number:</label>
                            <input type="text" name="emergency_contact_phone" placeholder="Phone Number" style="width: 100%;">
                        </div>
                    
                        <!-- Submit -->
                        <div class="btn-wrapper">
                            <button class="theme-btn-1 btn reverse-color btn-block" type="submit">SUBMIT QUESTIONNAIRE</button>
                        </div>
                    </form>
<?php include("./footer.php"); ?>
