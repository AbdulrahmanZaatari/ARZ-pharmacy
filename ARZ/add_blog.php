<?php
session_start();
include("./role_based_header.php");

// Restrict access to pharmacists
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pharmacist') {
    header("Location: index.php");
    exit();
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("./connection.php");

    // Log the POST request data
    file_put_contents('logs.txt', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);
    file_put_contents('logs.txt', "FILES Data: " . print_r($_FILES, true) . "\n", FILE_APPEND);

    // Check if user_id is set in the session
    if (!isset($_SESSION['pharmacist_id'])) {
        file_put_contents('logs.txt', "Error: user_id not found in session.\n", FILE_APPEND);
        echo "<script>alert('Error: Unable to identify user. Please log in again.'); window.location.href='pharmacistLogin.php';</script>";
        exit();
    }

    $author_id = $_SESSION['pharmacist_id']; // Pharmacist ID from session
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $video_link = !empty($_POST['video_link']) ? $conn->real_escape_string($_POST['video_link']) : null;

    // Handle image upload
    $image_path = null;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/blogs/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            file_put_contents('logs.txt', "Image uploaded successfully: $image_path\n", FILE_APPEND);
        } else {
            file_put_contents('logs.txt', "Image upload failed.\n", FILE_APPEND);
            $image_path = null; // Reset image path if upload failed
        }
    }

    // Insert into blogs table
    $sql = "INSERT INTO blogs (title, content, image_path, video_link, author_id) VALUES ('$title', '$content', '$image_path', '$video_link', '$author_id')";
    if ($conn->query($sql)) {
        file_put_contents('logs.txt', "Blog inserted successfully.\n", FILE_APPEND);
        echo "<script>alert('Blog added successfully!'); window.location.href='blog.php';</script>";
    } else {
        file_put_contents('logs.txt', "Database error: " . $conn->error . "\n", FILE_APPEND);
        echo "<script>alert('Error adding blog: " . $conn->error . "');</script>";
    }
}
?>

<div class="container mt-5">
    <h2>Add New Blog</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="video_link" class="form-label">Video Link (Optional)</label>
            <input type="url" class="form-control" id="video_link" name="video_link">
        </div>
        <button type="submit" class="btn btn-primary">Add Blog</button>
    </form>
</div>

<?php
include("./pharmacist_footer.php");
?>
