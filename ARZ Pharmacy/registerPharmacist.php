<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include("./connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer data if logged in
$customerData = [];
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT first_name, last_name, phone_number, address FROM customers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $customerData = $result->fetch_assoc();
    }
}

// Initialize response message
$message = "";

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize and validate input
        $first_name = $conn->real_escape_string($_POST['firstname']);
        $last_name = $conn->real_escape_string($_POST['lastname']);
        $phone_number = $conn->real_escape_string($_POST['phone_number']);
        $address = $conn->real_escape_string($_POST['address']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
        $license_number = $conn->real_escape_string($_POST['license_number']);
        $degree = $conn->real_escape_string($_POST['degree']);
        $certifications = $conn->real_escape_string($_POST['certifications']);

        // Handle file upload
        $profile_picture = "";
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "uploads/pharmacist_requests/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Create directory if not exists
            }
            $profile_picture = $upload_dir . basename($_FILES['profile_picture']['name']);
            if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture)) {
                throw new Exception("Failed to upload profile picture.");
            }
        }

        // Insert into `pharmacist_verification_requests` table
        $requestSQL = "
            INSERT INTO pharmacist_verification_requests 
            (first_name, last_name, phone_number, address, email, password, profile_picture, license_number, degree, certifications, request_date, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending')";
        $requestStmt = $conn->prepare($requestSQL);

        if (!$requestStmt) {
            throw new Exception("Failed to prepare pharmacist request query: " . $conn->error);
        }

        $requestStmt->bind_param(
            "ssssssssss",
            $first_name,
            $last_name,
            $phone_number,
            $address,
            $email,
            $password,
            $profile_picture,
            $license_number,
            $degree,
            $certifications
        );

        if (!$requestStmt->execute()) {
            throw new Exception("Failed to insert pharmacist request: " . $requestStmt->error);
        }

        // Success message
        $message = "<div class='alert alert-success'>Pharmacist request submitted successfully. Please wait for verification.</div>";
    } catch (Exception $e) {
        // Error message
        error_log("Error: " . $e->getMessage());
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!-- HTML FORM -->
<?php include("./role_based_header.php"); ?>

<div class="ltn__login-area pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="account-login-inner">
                    <!-- Display Message -->
                    <?php echo $message; ?>

                    <form action="registerPharmacist.php" method="POST" enctype="multipart/form-data" class="ltn__form-box contact-form-box">
                        <h3>Register as Pharmacist</h3>
                        <input type="text" name="firstname" placeholder="First Name" value="<?php echo htmlspecialchars($customerData['first_name'] ?? ''); ?>" required>
                        <input type="text" name="lastname" placeholder="Last Name" value="<?php echo htmlspecialchars($customerData['last_name'] ?? ''); ?>" required>
                        <input type="text" name="phone_number" placeholder="Phone Number" value="<?php echo htmlspecialchars($customerData['phone_number'] ?? ''); ?>" required>
                        <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($customerData['address'] ?? ''); ?>" required>
                        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <input type="text" name="license_number" placeholder="License Number" required>
                        <textarea name="degree" placeholder="Degree Information" required></textarea>
                        <textarea name="certifications" placeholder="Certifications" required></textarea>
                        <div class="mb-3">
                            <label>Upload Profile Picture</label>
                            <input type="file" name="profile_picture" accept="image/*" required>
                        </div>
                        <div class="btn-wrapper">
                            <button class="theme-btn-1 btn reverse-color btn-block" type="submit">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("./footer.php"); ?>
