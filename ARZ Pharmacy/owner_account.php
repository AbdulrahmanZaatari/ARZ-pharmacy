<?php
session_start();
include("./connection.php");

// Ensure the user is logged in
if (!isset($_SESSION['owner_email'])) {
    header("Location: ownerLogin.php");
    exit();
}

// Fetch logged-in owner's details
$owner_email = $_SESSION['owner_email'];
$sql_owner = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, profile_picture FROM owners WHERE email = ?";
$stmt_owner = $conn->prepare($sql_owner);
$stmt_owner->bind_param("s", $owner_email);
$stmt_owner->execute();
$result_owner = $stmt_owner->get_result();
$owner = $result_owner->fetch_assoc();
$owner_id = $owner['id'];
$owner_name = $owner['full_name'];
$profile_picture = $owner['profile_picture'];

// Fetch total sales for the day
$sql_sales = "SELECT SUM(total_price) AS total_sales FROM sales_records WHERE DATE(sale_date) = CURDATE()";
$result_sales = $conn->query($sql_sales);
$total_sales = $result_sales->fetch_assoc()['total_sales'] ?? 0;

// Fetch pending pharmacist verification requests
$sql_requests = "SELECT COUNT(*) AS pending_requests FROM pharmacist_verification_requests WHERE status = 'pending'";
$result_requests = $conn->query($sql_requests);
$pending_requests = $result_requests->fetch_assoc()['pending_requests'];

// Fetch current pharmacists
$sql_pharmacists = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, profile_picture, status FROM pharmacists";
$result_pharmacists = $conn->query($sql_pharmacists);

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
                    <h1 class="page-title">Owner Dashboard</h1>
                    <p>Welcome back, <strong><?php echo htmlspecialchars($owner_name); ?></strong>!</p>
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
                        <a class="active show" data-bs-toggle="tab" href="#sales_tab">Sales<i class="fas fa-chart-line"></i></a>
                        <a data-bs-toggle="tab" href="#verification_requests_tab">Verification Requests<i class="fas fa-envelope"></i></a>
                        <a data-bs-toggle="tab" href="#pharmacists_tab">Pharmacists<i class="fas fa-user-md"></i></a>
                        <a data-bs-toggle="tab" href="#account_details_tab">Account Details<i class="fas fa-user"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="tab-content">
                    <!-- Sales Tab -->
                    <div class="tab-pane fade active show" id="sales_tab">
                        <div class="ltn__myaccount-tab-content-inner">
                            <h4>Total Sales for Today</h4>
                            <p><strong>Total:</strong> $<?php echo number_format($total_sales, 2); ?></p>
                            <a href="daily_sales.php" class="btn btn-primary">View Full Sales Page</a>
                        </div>
                    </div>
                    <!-- Verification Requests Tab -->
                    <div class="tab-pane fade" id="verification_requests_tab">
                        <div class="ltn__myaccount-tab-content-inner">
                            <h4>Pending Verification Requests</h4>
                            <p>You have <strong><?php echo $pending_requests; ?></strong> pending requests.</p>
                            <a href="verificationRequests.php" class="btn btn-primary">View All Requests</a>
                        </div>
                    </div>
                     <!-- Pharmacists Tab -->
<div class="tab-pane fade" id="pharmacists_tab">
    <div class="ltn__myaccount-tab-content-inner">
        <h4>Current Pharmacists</h4>
        <div class="row" id="pharmacists-list">
            <?php while ($pharmacist = $result_pharmacists->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm" style="border: 1px solid #ddd; border-radius: 8px;" id="pharmacist-<?php echo $pharmacist['id']; ?>">
                    <img src=<?php echo htmlspecialchars($pharmacist['profile_picture']); ?>
                         alt="Profile" 
                         class="card-img-top" 
                         style="width: 100%; height: 200px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-2"><?php echo htmlspecialchars($pharmacist['full_name']); ?></h5>
                        <p class="card-text">
                            <span class="badge status-badge <?php echo ($pharmacist['status'] === 'active') ? 'bg-success' : 'bg-danger'; ?>">
                                Status: <span id="status-text-<?php echo $pharmacist['id']; ?>"><?php echo htmlspecialchars($pharmacist['status']); ?></span>
                            </span>
                        </p>
                        <div class="d-grid gap-2">
                            <a href="assignTasks.php?pharmacist_id=<?php echo $pharmacist['id']; ?>" 
                               class="btn btn-outline-primary btn-sm">Assign Task</a>
                            <button type="button" 
                                    class="btn toggle-status-btn btn-<?php echo ($pharmacist['status'] === 'active') ? 'danger' : 'primary'; ?> btn-sm" 
                                    data-id="<?php echo $pharmacist['id']; ?>" 
                                    data-status="<?php echo $pharmacist['status']; ?>">
                                <?php echo ($pharmacist['status'] === 'active') ? 'Block' : 'Unblock'; ?>
                            </button>
                            <!-- Delete Button -->
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm delete-pharmacist-btn" 
                                    data-id="<?php echo $pharmacist['id']; ?>">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

                    <!-- Account Details Tab -->
                    <div class="tab-pane fade" id="account_details_tab">
                        <div class="ltn__myaccount-tab-content-inner">
                            <h4>Update Your Profile</h4>
                            <form action="update_owner_profile.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Profile Picture</label>
                                    <input type="file" name="profile_picture" class="form-control">
                                    <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile" class="img-thumbnail mt-2" style="width: 150px;">
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($owner_name); ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($owner_email); ?>" class="form-control">
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

<script>
    document.querySelectorAll('.toggle-status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pharmacistId = this.dataset.id;
            const currentStatus = this.dataset.status;

            // Make an AJAX request
            fetch('togglePharmacistStatus.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ pharmacist_id: pharmacistId, current_status: currentStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the UI
                    const newStatus = data.new_status;
                    const statusText = document.getElementById(`status-text-${pharmacistId}`);
                    const badge = document.querySelector(`#pharmacist-${pharmacistId} .status-badge`);
                    const button = document.querySelector(`#pharmacist-${pharmacistId} .toggle-status-btn`);

                    // Update status text and badge color
                    statusText.textContent = newStatus;
                    badge.classList.remove('bg-success', 'bg-danger');
                    badge.classList.add(newStatus === 'active' ? 'bg-success' : 'bg-danger');

                    // Update button text, class, and data-status
                    button.textContent = newStatus === 'active' ? 'Block' : 'Unblock';
                    button.classList.remove('btn-danger', 'btn-primary');
                    button.classList.add(newStatus === 'active' ? 'btn-danger' : 'btn-primary');
                    button.dataset.status = newStatus;
                } else {
                    alert('Failed to update the status. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });

    document.querySelectorAll('.delete-pharmacist-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pharmacistId = this.dataset.id;

            // Show confirmation popup
            if (confirm('Are you sure you want to delete this account?')) {
                fetch('deletePharmacist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ pharmacist_id: pharmacistId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove pharmacist card from the UI
                        document.getElementById(`pharmacist-${pharmacistId}`).remove();
                        alert('Pharmacist account has been successfully deleted.');
                    } else {
                        alert('Failed to delete the account. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });
</script>

<?php
$conn->close();
include("./pharmacist_footer.php");
?>
