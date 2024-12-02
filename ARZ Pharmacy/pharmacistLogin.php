<?php
session_start();
include("./connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM pharmacists WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $pharmacist = $result->fetch_assoc();

        // Check if the pharmacist is blocked
        if ($pharmacist['status'] === 'blocked') {
            $error = "Your account has been blocked. Please contact the administrator.";
        } elseif ($password === $pharmacist['password']) {
            // Successful login
            $_SESSION['pharmacist_id'] = $pharmacist['id'];
            $_SESSION['pharmacist_name'] = $pharmacist['first_name'] . " " . $pharmacist['last_name'];
            $_SESSION['pharmacist_email'] = $pharmacist['email'];
            $_SESSION['role'] = 'pharmacist'; 
            header("Location: pharmacist_account.php");
            exit();
        } else {
            // Invalid password
            $error = "Invalid email or password.";
        }
    } else {
        // Invalid email
        $error = "Invalid email or password.";
    }
    $stmt->close();
}
$conn->close();
?>

<?php include("./role_based_header.php")?>

<div class="ltn__utilize-overlay"></div>
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" style="height: 400px;" data-bs-bg="img/bg/login3.jpg">
</div>

<div class="ltn__login-area pb-65">
    <div class="container">
        <div class="row">
            <div class="section-title-area text-center">
                <h1 class="section-title">Sign In <br>To Your Pharmacist Account</h1>
                <p>Welcome Back to ARZ Pharmacy!<br> Please log in to access your pharmacist dashboard.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="account-login-inner box-style">
                    <h3 class="text-center">Login as Pharmacist</h3>
                    <form action="pharmacistLogin.php" method="POST" class="ltn__form-box contact-form-box">
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
                            <a href="#"><small>FORGOTTEN YOUR PASSWORD?</small></a>
                        </div>
                    </form>
                    <!-- Add Pharmacist Login Button -->
                    <div class="text-center mt-3">
                        <a href="login.php" class="theme-btn-2 btn btn-secondary">Customer Login</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="account-create box-style text-center">
                    <h3>Register as Pharmacist</h3>
                    <p>Submit your pharmacist registration and start contributing<br> to our platform today.</p>
                    <div class="btn-wrapper">
                        <a href="registerPharmacist.php" class="theme-btn-1 btn black-btn">REGISTER AS PHARMACIST</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
