<?php
session_start();
include("./connection.php");

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize messages
$success_message = "";
$error_message = "";

// Check if the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Use NULL for guests
$email = "";

// Fetch user email if logged in
if ($user_id) {
    $userQuery = $conn->prepare("SELECT email FROM customers WHERE id = ?");
    $userQuery->bind_param("i", $user_id);
    $userQuery->execute();
    $result = $userQuery->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $email = $user['email'];
    }
    $userQuery->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    // Get email from form if guest, or from the database if logged in
    $email = $user_id ? $email : $conn->real_escape_string($_POST['email']);

    // Insert data into the database
    $sql = "INSERT INTO questions (user_id, email, subject, message)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $email, $subject, $message);

    if ($stmt->execute()) {
        $success_message = "Your message has been sent successfully!";
    } else {
        $error_message = "Error: Could not send your message. Please try again later.";
    }
    $stmt->close();
}

include("./role_based_header.php");
?>

<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/AQ1.jpeg" style="background: url('img/bg/AQ1.jpeg') center/cover no-repeat; padding: 80px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__breadcrumb-inner text-center" style="color: white;">
                    <h1 class="page-title" style="font-size: 36px; font-weight: bold; margin-bottom: 10px;">Let's Ask!</h1>
                    <p style="font-size: 18px;">Have a question? Submit it here and get answers on the Q&A page!</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BREADCRUMB AREA END -->

<!-- CONTACT MESSAGE AREA START -->
<div class="ltn__contact-message-area mb-120 mb--100" style="margin-bottom: 40px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="ltn__form-box contact-form-box box-shadow white-bg p-4" style="border-radius: 8px; background: #f9f9f9;">
                    <h4 class="title-2 text-center mb-4" style="font-size: 24px; color: #333; font-weight: bold;">Ask Us a Question to Display on the Q&A Page!</h4>

                    <!-- Display success or error messages -->
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" style="font-size: 16px;"><?php echo $success_message; ?></div>
                    <?php elseif (!empty($error_message)): ?>
                        <div class="alert alert-danger" style="font-size: 16px;"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" style="font-weight: bold; color: #555;">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" style="background-color: <?php echo $user_id ? '#f9f9f9' : '#fff'; ?>; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" <?php echo $user_id ? 'readonly' : 'required'; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject" style="font-weight: bold; color: #555;">Subject</label>
                            <input type="text" class="form-control" name="subject" placeholder="Enter the subject" style="background-color: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                        </div>
                        <div class="form-group">
                            <label for="message" style="font-weight: bold; color: #555;">Message</label>
                            <textarea class="form-control" name="message" rows="6" placeholder="Enter your question" style="background-color: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required></textarea>
                        </div>
                        <div class="text-center mt-3">
                            <button class="btn btn-primary btn-block" type="submit" style="background-color: #007bff; border: none; font-size: 18px; padding: 10px 20px; border-radius: 5px; color: white;">Ask Question</button>
                        </div>
                        <p class="form-messege mb-0 mt-20"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CONTACT MESSAGE AREA END -->

<?php
if (isset($conn)) {
    $conn->close(); // Safely close the connection
}
include("./footer.php");
?>
