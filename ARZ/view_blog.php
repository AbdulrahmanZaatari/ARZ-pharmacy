<?php
session_start();
include("./role_based_header.php");
include("./connection.php");

// Check if blog ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('No blog selected.'); window.location.href='blog.php';</script>";
    exit();
}

// Fetch the blog details
$blog_id = intval($_GET['id']);
$query = "SELECT b.*, p.first_name AS author_name, p.last_name AS author_last_name 
          FROM blogs b
          JOIN pharmacists p ON b.author_id = p.id
          WHERE b.id = $blog_id";

$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    echo "<script>alert('Blog not found.'); window.location.href='blog.php';</script>";
    exit();
}

$blog = $result->fetch_assoc();
?>

<div class="container mt-5">
    <div class="blog-details">
        <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
        <p><strong>By:</strong> <?php echo htmlspecialchars($blog['author_name'] . " " . $blog['author_last_name']); ?></p>
        <p><small><strong>Published on:</strong> <?php echo htmlspecialchars($blog['created_at']); ?></small></p>
        <hr>
        <?php if (!empty($blog['image_path'])): ?>
            <img src="<?php echo htmlspecialchars($blog['image_path']); ?>" alt="Blog Image" style="max-width: 100%; height: auto; margin-bottom: 20px;">
        <?php endif; ?>
        <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
        <?php if (!empty($blog['video_link'])): ?>
            <p>
                <strong>Video:</strong> 
                <a href="<?php echo htmlspecialchars($blog['video_link']); ?>" target="_blank">Watch Video</a>
            </p>
        <?php endif; ?>
    </div>
    <a href="blog.php" class="btn btn-secondary mt-3">Back to Blogs</a>
</div>

<?php include("./pharmacist_footer.php"); ?>
