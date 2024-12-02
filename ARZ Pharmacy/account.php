<?php
session_start();
include("./connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch logged-in user's email
$user_email = $_SESSION['email'];
$customer_id = $_SESSION['user_id'];
// Fetch customer ID and full name for the logged-in user
$sql_customer = "SELECT id, first_name, last_name, profile_picture FROM customers WHERE email = ?";
$stmt_customer = $conn->prepare($sql_customer);
$stmt_customer->bind_param("s", $user_email);
$stmt_customer->execute();
$result_customer = $stmt_customer->get_result();
$customer = $result_customer->fetch_assoc();

// Fetching first and last name separately
$customer_first_name = $customer['first_name'];
$customer_last_name = $customer['last_name'];
$profile_picture = $customer['profile_picture'];

// Fetch user questions
$sql_own = "SELECT id, subject, created_at FROM questions WHERE email = ?";
$stmt_own = $conn->prepare($sql_own);
$stmt_own->bind_param("s", $user_email);
$stmt_own->execute();
$result_own = $stmt_own->get_result();

// Fetch public questions (questions not associated with the user's account)
$sql_public = "SELECT id, subject, created_at FROM questions WHERE email != ?";
$stmt_public = $conn->prepare($sql_public);
$stmt_public->bind_param("s", $user_email);
$stmt_public->execute();
$result_public = $stmt_public->get_result();

// Fetch orders for the logged-in customer
$sql_orders = "SELECT id, order_date, total_amount FROM orders WHERE customer_id = ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $customer_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

include("./role_based_header.php")
?>

<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/DB2.jpg" 
    style="background: url('img/bg/DB2.jpg') center/cover no-repeat; padding: 150px 0; text-align: center;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__breadcrumb-inner">
                    <h1 class="page-title" style="font-size: 36px; font-weight: 700; color: #2c3e50; text-shadow: 1px 1px 5px rgba(0,0,0,0.2);">
                        My Account
                    </h1>
                    <p style="font-size: 16px; color: #555; margin-top: 10px;">Access your account details, orders, and more!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BREADCRUMB AREA END -->

<!-- ACCOUNT AREA START -->
<div class="liton__wishlist-area pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- PRODUCT TAB AREA START -->
                <div class="ltn__product-tab-area">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="ltn__tab-menu-list mb-50">
                                    <div class="nav">
                                        <a class="active show" data-bs-toggle="tab" href="#liton_tab_1_1">Dashboard<i class="fas fa-home"></i></a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_2">Order History<i class="fas fa-file-alt"></i></a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_3">Questions<i class="fas fa-question"></i></a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_5">Account Details <i class="fas fa-user"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="tab-content">
                                    <!-- Dashboard Tab -->
                                    <div class="tab-pane fade active show" id="liton_tab_1_1">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <p style="font-size: 20px; font-weight: bold; margin-bottom: 10px;">
                                                Hello <span style="color: #28a745;"><?php echo htmlspecialchars($customer_first_name . $customer_last_name); ?></span>! 
                                            </p>
                                            <p style="font-size: 16px; color: #555; margin-bottom: 20px;">
                                                You can <a href="logout.php" style="color: #007bff; text-decoration: underline; font-weight: bold;">log out</a> directly from here.
                                            </p>
                                            <p style="font-size: 16px; line-height: 1.6;">
                                                From your account dashboard, you can view your recent orders, manage your shipping and billing addresses, and edit your account details.
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Orders History Tab -->
                                    <div class="tab-pane fade" id="liton_tab_1_2">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <label for="order-date-filter">Filter by Date:</label>
                                            <input type="date" id="order-date-filter" class="form-control mb-3">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Order ID</th>
                                                            <th>Date</th>
                                                            <th>Total</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="orders-body">
                                                        <!-- Orders will be loaded dynamically -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Questions Tab -->
                                    <div class="tab-pane fade" id="liton_tab_1_3">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <div class="btn-group mb-3">
                                                <button class="btn theme-btn-1 btn-effect-1 text-uppercase" id="btn-own-questions">My Questions</button>
                                                <button class="btn theme-btn-1 btn-effect-1 text-uppercase" id="btn-public-questions">Public Questions</button>
                                            </div>
                                            <div class="mb-3">
                                                <label for="question-date-filter">Filter by Date:</label>
                                                <input type="date" id="question-date-filter" class="form-control">
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table" id="questions-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Subject</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="questions-body">
                                                        <!-- Questions will be loaded dynamically -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Account Details Tab -->
                                    <div class="tab-pane fade" id="liton_tab_1_5">
                                        <div class="ltn__myaccount-tab-content-inner">
                                        <h4>Update Your Profile</h4>
                                        <form action="update_customer_profile.php" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                                <label>Profile Picture</label>
                                                <input type="file" name="profile_picture" class="form-control">
                                                <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile" class="img-thumbnail mt-2" style="width: 150px;">
                                        </div>
                                                    <div class="form-group">
                                                        <label>First Name</label>
                                                        <input type="text" name="first" value="<?php echo htmlspecialchars($customer_first_name); ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Last Name</label>
                                                        <input type="text" name="last" value="<?php echo htmlspecialchars($customer_last_name); ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" class="form-control">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-3">Update Profile</button>
                                                </form>
                                                <button onclick="location.href='modify_ehr.php'" class="btn btn-warning mt-3" style="margin-top: 20px; margin-bottom: 20px;">Modify EHR</button>
                                            </div>
                <!-- PRODUCT TAB AREA END -->
            </div>
        </div>
    </div>
</div>
<!-- ACCOUNT AREA END -->

<script>
    const userQuestions = <?php echo json_encode($result_own->fetch_all(MYSQLI_ASSOC)); ?>;
    const publicQuestions = <?php echo json_encode($result_public->fetch_all(MYSQLI_ASSOC)); ?>;
    const allOrders = <?php echo json_encode($result_orders->fetch_all(MYSQLI_ASSOC)); ?>;

    const questionsBody = document.getElementById("questions-body");
    const ordersBody = document.getElementById("orders-body");

    let currentQuestionsView = 'my';

    function displayQuestions(questions) {
        questionsBody.innerHTML = "";
        if (questions.length > 0) {
            questions.forEach(question => {
                const row = `
                    <tr>
                        <td>${new Date(question.created_at).toLocaleDateString()}</td>
                        <td>${question.subject}</td>
                        <td>
                            <a href="view_answer.php?question_id=${question.id}" 
                               class="btn btn-effect-3 btn-white" 
                               style="background-color: #1e8449; color: white; padding: 8px 16px; border-radius: 5px;">
                               View Answer
                            </a>
                        </td>
                    </tr>
                `;
                questionsBody.innerHTML += row;
            });
        } else {
            questionsBody.innerHTML = "<tr><td colspan='3'>No questions found.</td></tr>";
        }
    }

    function displayOrders(orders) {
        ordersBody.innerHTML = "";
        if (orders.length > 0) {
            orders.forEach(order => {
                const row = `
                    <tr>
                        <td>${order.id}</td>
                        <td>${new Date(order.order_date).toLocaleDateString()}</td>
                        <td>${order.total_amount} $</td>
                        <td><a href="viewingOrder.php?order_id=${order.id}" class="btn btn-effect-3 btn-white">View</a></td>
                    </tr>
                `;
                ordersBody.innerHTML += row;
            });
        } else {
            ordersBody.innerHTML = "<tr><td colspan='5'>No orders found.</td></tr>";
        }
    }

    document.getElementById("btn-own-questions").addEventListener("click", () => {
        currentQuestionsView = 'my';
        displayQuestions(userQuestions);
    });

    document.getElementById("btn-public-questions").addEventListener("click", () => {
        currentQuestionsView = 'public';
        displayQuestions(publicQuestions);
    });

    document.getElementById("question-date-filter").addEventListener("input", (e) => {
        const selectedDate = e.target.value ? new Date(e.target.value).toLocaleDateString() : null;
        if (!selectedDate) {
            if (currentQuestionsView === 'my') {
                displayQuestions(userQuestions);
            } else {
                displayQuestions(publicQuestions);
            }
        } else {
            if (currentQuestionsView === 'my') {
                displayQuestions(userQuestions.filter(q => new Date(q.created_at).toLocaleDateString() === selectedDate));
            } else {
                displayQuestions(publicQuestions.filter(q => new Date(q.created_at).toLocaleDateString() === selectedDate));
            }
        }
    });

    document.getElementById("order-date-filter").addEventListener("change", (e) => {
        const selectedDate = new Date(e.target.value).toLocaleDateString();
        displayOrders(allOrders.filter(o => new Date(o.order_date).toLocaleDateString() === selectedDate));
    });

    displayQuestions(userQuestions);
    displayOrders(allOrders);
</script>

<?php
$conn->close();
include("./pharmacist_footer.php");
?>

