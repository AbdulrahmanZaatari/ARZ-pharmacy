<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

include("./role_based_header.php");
?>

<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/14.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__breadcrumb-inner">
                    <h1 class="page-title">Account</h1>
                    <div class="ltn__breadcrumb-list">
                        <ul>
                            <li><a href="index.php"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                            <li>Register</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BREADCRUMB AREA END -->

<!-- LOGIN AREA START (Register) -->
<div class="ltn__login-area pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area text-center">
                    <h1 class="section-title">Register <br>Your Account</h1>
                    <p>Are you ready for personalized treatment?<br> Join us today!</p>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-3">
                <div class="account-login-inner">
                    <form action="" method="POST" class="ltn__form-box contact-form-box">
                        <h3>Account Creation</h3>
                        <input type="text" name="firstname" placeholder="First Name" required>
                        <input type="text" name="lastname" placeholder="Last Name" required>
                        <div class="mb-3">
                            <label for="birth_date">Date of Birth:</label>
                            <input type="date" id="birth_date" name="birth_date" required>
                        </div>
                        <input type="email" name="email" placeholder="Email*" required>
                        <input type="text" name="phone_number" placeholder="Phone Number*" required>
                        <input type="text" name="address" placeholder="Address*" required>
                        <input type="password" name="password" placeholder="Password*" required>
                        <input type="password" name="confirmpassword" placeholder="Confirm Password*" required>
                        <h4>Security Questions</h4>
                        <input type="text" name="security_question1" placeholder="What's your grandma's name?" required>
                        <input type="text" name="security_question2" placeholder="What's your pet's name?" required>
                        <label class="checkbox-inline">
                            <input type="checkbox" value="" required>
                            I consent to ARZ Pharmacy processing my personal data in accordance with the consent form and the privacy policy.
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" value="" required>
                            By clicking "create account", I consent to the privacy policy.
                        </label>
                        <div class="btn-wrapper">
                            <button class="theme-btn-1 btn reverse-color btn-block" type="submit">CREATE ACCOUNT</button>
                        </div>
                    </form>
                    <div class="by-agree text-center">
                        <p>By creating an account, you agree to our:</p>
                        <p><a href="#">TERMS OF CONDITIONS &nbsp; &nbsp; | &nbsp; &nbsp; PRIVACY POLICY</a></p>
                        <div class="go-to-btn mt-50">
                            <a href="login.php">ALREADY HAVE AN ACCOUNT?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- LOGIN AREA END -->

<?php
include("./footer.php");
?>
