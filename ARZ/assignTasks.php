<?php
session_start();
include("./connection.php");

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerLogin.php");
    exit();
}

// Retrieve pharmacist_id from the URL
if (isset($_GET['pharmacist_id']) && is_numeric($_GET['pharmacist_id'])) {
    $pharmacist_id = $_GET['pharmacist_id'];
} else {
    // Redirect back if pharmacist_id is not valid or missing
    echo "<div class='alert alert-danger'>Invalid pharmacist ID.</div>";
    exit();
}

// Fetch pharmacist details to display on the form (optional)
$sql_pharmacist = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM pharmacists WHERE id = ?";
$stmt = $conn->prepare($sql_pharmacist);
$stmt->bind_param("i", $pharmacist_id);
$stmt->execute();
$result_pharmacist = $stmt->get_result();
$pharmacist = $result_pharmacist->fetch_assoc();

if (!$pharmacist) {
    echo "<div class='alert alert-danger'>Pharmacist not found.</div>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_type = $_POST['task_type'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $assigned_by = $_SESSION['owner_id']; // Assuming owner is logged in

    // Validate task type
    if (empty($task_type)) {
        echo "<div class='alert alert-warning'>Task type is required.</div>";
    } else {
        // Insert task into the database
        $sql = "INSERT INTO task_assignments (pharmacist_id, task_type, description, due_date, assigned_by) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $pharmacist_id, $task_type, $description, $due_date, $assigned_by);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Task assigned successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    }
}
?>

<!-- Improved Task Assignment Form -->
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">Assign Task to <?php echo htmlspecialchars($pharmacist['full_name']); ?></h2>

        <form action="assignTasks.php?pharmacist_id=<?php echo $pharmacist_id; ?>" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="task_type" class="font-weight-bold">Task Type</label>
                        <select name="task_type" class="form-control custom-select" required>
                            <option value="" disabled selected class="placeholder">Choose Task</option> <!-- Default hint text -->
                            <option value="communication">Communication</option>
                            <option value="manage_products">Manage Products</option>
                            <option value="review_requests">Review Requests</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="description" class="font-weight-bold">Task Description</label>
                        <textarea name="description" class="form-control custom-textarea" rows="6" required></textarea> <!-- Increased height for rectangular look -->
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="due_date" class="font-weight-bold">Due Date</label>
                <input type="date" name="due_date" class="form-control custom-input" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn custom-btn">Assign Task</button>
            </div>
        </form>
    </div>
</div>

<!-- Custom CSS Styles -->
<style>
    /* Custom Font */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fa;
        padding: 20px; /* Increased padding to give space around the page */
    }

    /* Card Design - Make card span full width */
    .card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%; /* Make card full width */
        padding: 30px; /* Added padding to prevent words sticking to the edge of the card */
    }

    /* Title */
    h2 {
        font-size: 28px;
        color: #2c3e50;
        font-weight: 600;
    }

    /* Form Control Styling */
    .form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
    }

    /* Custom Select Box */
    .custom-select {
        background-color: #f4f7fa;
        border-radius: 8px;
    }

    /* Placeholder styling */
    .placeholder {
        color: #888; /* Light grey to give a hint-like effect */
    }

    /* Textarea - Elongated Rectangle */
    .custom-textarea {
        resize: vertical;
        background-color: #f4f7fa;
        border-radius: 8px;
    }

    /* Custom Input Field */
    .custom-input {
        background-color: #f4f7fa;
        border-radius: 8px;
    }

    /* Submit Button */
    .custom-btn {
        background-color: #3498db;
        color: white;
        padding: 12px 30px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .custom-btn:hover {
        background-color: #2980b9;
    }

    /* Spacing between form elements */
    .mb-3, .mb-4 {
        margin-bottom: 20px;
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        padding: 15px;
        font-size: 16px;
        text-align: center;
    }

    .alert-success {
        background-color: #28a745;
        color: white;
    }

    .alert-danger {
        background-color: #dc3545;
        color: white;
    }

    .alert-warning {
        background-color: #ffc107;
        color: white;
    }

    /* Form Container */
    .container {
        max-width: 100%; /* Full width for the container */
        padding-left: 20px;
        padding-right: 20px;
    }

    /* Spacing adjustments for large screens */
    @media (min-width: 992px) {
        .container {
            max-width: 900px;
        }
    }

</style>
