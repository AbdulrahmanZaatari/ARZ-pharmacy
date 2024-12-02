<?php
session_start();

// Include database connection (Update with your database credentials)
include("./connection.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // Initialize error message

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM customers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'customer';

            // Redirect to dashboard
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<?php include("./role_based_header.php") ?>

<!-- LOGIN PAGE CONTENT -->
<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" style="height: 400px;" data-bs-bg="img/bg/login3.jpg">
</div>
<!-- BREADCRUMB AREA END -->

<!-- LOGIN AREA START -->
<div class="ltn__login-area pb-65">
    <div class="container">
        <div class="row">
            <div class="section-title-area text-center">
                <h1 class="section-title">Sign In <br>To Your Account</h1>
                <p>Welcome Back to ARZ Pharmacy!</p>
            </div>
        </div>
        <div class="row">
            <!-- Login Box -->
            <div class="col-lg-6">
    <div class="account-login-inner box-style">
        <h3 class="text-center">Login</h3>
        <form action="login.php" method="POST" class="ltn__form-box contact-form-box">
            <input type="email" name="email" placeholder="Email*" required>
            <input type="password" name="password" placeholder="Password*" required>
            <div class="btn-wrapper mt-0">
                <button class="theme-btn-1 btn btn-block" type="submit">SIGN IN</button>
            </div>
            <!-- Display error message -->
            <?php if (!empty($error)): ?>
                <p style="color: red; margin-top: 10px;"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="go-to-btn mt-20">
                <a href="forgot_password.php"><small>FORGOTTEN YOUR PASSWORD?</small></a>
            </div>
        </form>
        <!-- Add Pharmacist Login Button -->
        <div class="text-center mt-3">
            <a href="pharmacistLogin.php" class="theme-btn-2 btn btn-secondary">Pharmacist Login</a>
        </div>
    </div>
</div>


            <!-- Register Box -->
            <div class="col-lg-6">
                <div class="account-create box-style text-center">
                    <h3>Create Account</h3>
                    <p>Register so that you can add items to your wishlist and get personalized recommendations and services!</p>
                    <div class="btn-wrapper">
                        <a href="register.php" class="theme-btn-1 btn black-btn">CREATE ACCOUNT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- LOGIN AREA END -->

<style>
    .box-style {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .box-style h3 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .box-style .btn {
        width: 100%;
        margin-top: 20px;
    }

    .box-style p {
        color: #555;
        margin-bottom: 20px;
    }

    .account-login-inner {
        padding: 20px;
    }
</style>


<?php include("./footer.php"); ?>
