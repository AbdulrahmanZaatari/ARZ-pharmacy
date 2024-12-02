<?php
session_start();
include("./connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['pharmacist_email'])) {
    header("Location: pharmacistLogin.php");
    exit();
}

// Fetch logged-in pharmacist's email
$pharmacist_email = $_SESSION['pharmacist_email'];

// Fetch pharmacist details
$sql_pharmacist = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, profile_picture FROM pharmacists WHERE email = ?";
$stmt_pharmacist = $conn->prepare($sql_pharmacist);
$stmt_pharmacist->bind_param("s", $pharmacist_email);
$stmt_pharmacist->execute();
$result_pharmacist = $stmt_pharmacist->get_result();
$pharmacist = $result_pharmacist->fetch_assoc();
$pharmacist_id = $pharmacist['id'];
$pharmacist_name = $pharmacist['full_name'];
$profile_picture = $pharmacist['profile_picture'];

// Fetch pending requests count
$sql_requests = "SELECT COUNT(*) AS pending_requests FROM approval_requests WHERE status = 'pending'";
$result_requests = $conn->query($sql_requests);
$pending_requests = $result_requests->fetch_assoc()['pending_requests'];

// Fetch pharmacist's tasks
$sql_tasks = "
    SELECT 
        task_assignments.id, task_type, description, due_date, owners.first_name AS assigned_by_name, assigned_at 
    FROM 
        task_assignments 
    INNER JOIN 
        owners 
    ON 
        task_assignments.assigned_by = owners.id
    WHERE 
        pharmacist_id = ?";
$stmt_tasks = $conn->prepare($sql_tasks);
$stmt_tasks->bind_param("i", $pharmacist_id);
$stmt_tasks->execute();
$result_tasks = $stmt_tasks->get_result();

// Fetch all questions (pharmacists can view all questions)
$sql_questions = "SELECT id, subject, created_at FROM questions";
$result_questions = $conn->query($sql_questions);

include("./role_based_header.php");
?>

<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/DB2.jpg" 
    style="background: url('img/bg/DB2.jpg') center/cover no-repeat; padding: 150px 0; text-align: center;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__breadcrumb-inner">
                    <h1 class="page-title">Pharmacist Dashboard</h1>
                    <p>Welcome back, <strong><?php echo htmlspecialchars($pharmacist_name); ?></strong>!</p>
                </div>
                <div class="text-center mt-3">
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
            <div class="col-lg-4">
                <div class="ltn__tab-menu-list mb-50">
                    <div class="nav">
                        <a class="active show" data-bs-toggle="tab" href="#dashboard_tab">Dashboard<i class="fas fa-home"></i></a>
                        <a data-bs-toggle="tab" href="#tasks_tab">Tasks<i class="fas fa-tasks"></i></a>
                        <a data-bs-toggle="tab" href="#questions_tab">Questions<i class="fas fa-question"></i></a>
                        <a data-bs-toggle="tab" href="#requests_tab">Requests<i class="fas fa-envelope"></i> (<?php echo $pending_requests; ?>)</a>
                        <a data-bs-toggle="tab" href="#account_details_tab">Account Details<i class="fas fa-user"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade active show" id="dashboard_tab">
                        <div class="ltn__myaccount-tab-content-inner">
                            <h4>Dashboard</h4>
                            <p>Hello <strong><?php echo htmlspecialchars($pharmacist_name); ?></strong>! You can manage your tasks, view questions, and update your profile here.</p>
                        </div>
                    </div>
                    <!-- Tasks Tab -->
                    <div class="tab-pane fade" id="tasks_tab">
                        <div class="ltn__myaccount-tab-content-inner">
                            <h4>Your Tasks</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Task Type</th>
                                            <th>Description</th>
                                            <th>Due Date</th>
                                            <th>Assigned By</th>
                                            <th>Assigned At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($task = $result_tasks->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($task['task_type']); ?></td>
                                            <td><?php echo htmlspecialchars($task['description']); ?></td>
                                            <td><?php echo date("F d, Y", strtotime($task['due_date'])); ?></td>
                                            <td><?php echo htmlspecialchars($task['assigned_by_name']); ?></td>
                                            <td><?php echo date("F d, Y", strtotime($task['assigned_at'])); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Questions Tab -->
                    <div class="tab-pane fade" id="questions_tab">
                        <div class="ltn__myaccount-tab-content-inner">
                            <h4>All Questions</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Subject</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($question = $result_questions->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo date("F d, Y", strtotime($question['created_at'])); ?></td>
                                            <td><?php echo htmlspecialchars($question['subject']); ?></td>
                                            <td>
                                                <a href="view_answer.php?question_id=<?php echo $question['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <a href="publicQ&A.php" class="btn btn-secondary">Go to Full Q&A Page</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Requests Tab -->
                    <div class="tab-pane fade" id="requests_tab">
                        <div class="ltn__myaccount-tab-content-inner">
                            <h4>Pending Requests</h4>
                            <p>You have <strong><?php echo $pending_requests; ?></strong> pending requests.</p>
                            <a href="requestPharmacist.php" class="btn btn-primary">View All Requests</a>
                        </div>
                    </div>
                    <!-- Account Details Tab -->
                    <div class="tab-pane fade" id="account_details_tab">
                        <div class="ltn__myaccount-tab-content-inner">
                            <h4>Update Your Profile</h4>
                            <form action="update_pharmacist_profile.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Profile Picture</label>
                                    <input type="file" name="profile_picture" class="form-control">
                                    <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile" class="img-thumbnail mt-2" style="width: 150px;">
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($pharmacist_name); ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($pharmacist_email); ?>" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ACCOUNT AREA END -->

<?php
$conn->close();
include("./pharmacist_footer.php");
?>
