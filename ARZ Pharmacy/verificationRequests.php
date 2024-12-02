<?php
session_start();
include("./connection.php");

// Ensure the user is logged in
if (!isset($_SESSION['owner_email'])) {
    header("Location: ownerLogin.php");
    exit();
}

// Fetch pending verification requests
$sql_requests = "SELECT id, first_name, last_name, email, license_number, profile_picture, request_date, birth_date FROM pharmacist_verification_requests WHERE status = 'pending'";
$result_requests = $conn->query($sql_requests);


include("./role_based_header.php");
?>

<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" 
    style="background: url('img/bg/requests.jpg') center/cover no-repeat; padding: 10px 0; text-align: center;">
    <div class="container">
        <h1 class="page-title" style="margin: 0;">Pending Verification Requests</h1>
    </div>
</div>
<!-- BREADCRUMB AREA END -->

<!-- ACCOUNT AREA START -->
<div class="liton__wishlist-area pb-70">
    <div class="container">
        <div class="row g-4 mt-3">
            <?php if ($result_requests->num_rows > 0): ?>
                <?php while ($request = $result_requests->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm" style="border-radius: 10px;">
                            <div class="card-body text-center">
                                <img src="<?php echo htmlspecialchars($request['profile_picture']); ?>" 
                                     alt="Profile Picture" 
                                     class="img-thumbnail mb-3" 
                                     style="width: 100%; height: auto; border-radius: 10px;">
                                <h5 class="card-title mb-3"><?php echo htmlspecialchars($request['first_name'] . " " . $request['last_name']); ?></h5>
                                <p class="mb-2">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($request['email']); ?><br>
                                    <strong>License:</strong> <?php echo htmlspecialchars($request['license_number']); ?><br>
                                    <strong>Request Date:</strong> <?php echo htmlspecialchars(date("F j, Y", strtotime($request['request_date']))); ?>
                                </p>
                                <button type="button" 
                                        class="btn btn-primary btn-sm view-details-btn" 
                                        data-id="<?php echo $request['id']; ?>">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No pending verification requests at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- ACCOUNT AREA END -->

<!-- MODAL AREA START -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Verification Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="modal-profile-picture" src="" alt="Profile Picture" class="img-thumbnail mb-3" style="width: 100%; height: auto;">
                    </div>
                    <div class="col-md-8">
                        <h5 id="modal-name"></h5>
                        <p id="modal-email"></p>
                        <p id="modal-license"></p>
                        <p id="modal-request-date"></p>
                        <p id="modal-degree"></p>
                        <p id="modal-certifications"></p>
                        <p id="modal-birth-date"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success approve-request-btn">Approve</button>
                <button type="button" class="btn btn-danger reject-request-btn">Reject</button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL AREA END -->

<script>
   document.addEventListener('DOMContentLoaded', function () {
    // Handle View Details Button
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function () {
            const requestId = this.dataset.id;

            // Fetch data for the modal
            fetch('getVerificationDetails.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ request_id: requestId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate modal with data
                    document.getElementById('modal-profile-picture').src = data.profile_picture;
                    document.getElementById('modal-name').textContent = `Name: ${data.first_name} ${data.last_name}`;
                    document.getElementById('modal-email').textContent = `Email: ${data.email}`;
                    document.getElementById('modal-license').textContent = `License: ${data.license_number}`;
                    document.getElementById('modal-request-date').textContent = `Request Date: ${data.request_date}`;
                    document.getElementById('modal-degree').textContent = `Degree: ${data.degree}`;
                    document.getElementById('modal-certifications').textContent = `Certifications: ${data.certifications}`;
                    document.getElementById('modal-birth-date').textContent = `Birth Date: ${data.birth_date}`;

                    // Attach IDs for actions
                    document.querySelector('.approve-request-btn').dataset.id = requestId;
                    document.querySelector('.reject-request-btn').dataset.id = requestId;

                    // Show the modal
                    const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                    detailsModal.show();
                } else {
                    alert('Failed to fetch details. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
    // Handle Approve Button
    document.querySelector('.approve-request-btn').addEventListener('click', function () {
        const requestId = this.dataset.id;

        fetch('approveVerificationRequest.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ request_id: requestId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Request approved successfully!');
                document.querySelector(`[data-id="${requestId}"]`).closest('.card').remove(); // Remove the card
            } else {
                alert('Failed to approve the request: ' + data.error);
            }
        });
    });

    // Handle Reject Button
    document.querySelector('.reject-request-btn').addEventListener('click', function () {
    const requestId = this.dataset.id;

    fetch('rejectVerificationRequest.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ request_id: requestId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close the modal
            const detailsModal = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
            detailsModal.hide();

            // Remove the card
            document.querySelector(`[data-id="${requestId}"]`).closest('.card').remove();

            // Show success message
            alert('Request rejected successfully!');
        } else {
            alert('Failed to reject the request: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while rejecting the request.');
    });
});

});
</script>

<?php
$conn->close();
include("./pharmacist_footer.php");
?>
