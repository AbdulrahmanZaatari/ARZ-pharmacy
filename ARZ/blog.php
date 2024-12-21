<?php
session_start();
include("./role_based_header.php");
?>

<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
     <h1 style="text-align: center; margin: 20px 0;">Articles & Blogs</h1>
<!-- BLOG AREA START -->
<div class="ltn__blog-area mb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Add Blog Button for Pharmacists -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'pharmacist'): ?>
                    <div style="text-align: right; margin-bottom: 20px;">
                        <a href="add_blog.php" class="btn btn-primary" style="background-color: #007bff; border: none; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold;">Add Blog</a>
                    </div>
                <?php endif; ?>

                <!-- Blog Items Section -->
                <?php
                include("./connection.php");

                // Fetch blogs from the database
                $query = "SELECT b.*, p.first_name AS author_name 
                          FROM blogs b 
                          JOIN pharmacists p ON b.author_id = p.id 
                          ORDER BY b.created_at DESC";

                $result = $conn->query($query);

                if ($result && $result->num_rows > 0): ?>
                    <div class="row">
                        <?php while ($blog = $result->fetch_assoc()): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card" style="border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                    <?php if (!empty($blog['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($blog['image_path']); ?>" class="card-img-top" alt="Blog Image" style="height: 200px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                                        <p class="card-text" style="color: #555;"><?php echo nl2br(htmlspecialchars(substr($blog['content'], 0, 100))); ?>...</p>
                                        <p class="text-muted mb-1"><small>By: <?php echo htmlspecialchars($blog['author_name']); ?></small></p>
                                        <p class="text-muted"><small>Published on: <?php echo htmlspecialchars($blog['created_at']); ?></small></p>
                                        <?php if (!empty($blog['video_link'])): ?>
                                            <a href="<?php echo htmlspecialchars($blog['video_link']); ?>" class="btn btn-link" target="_blank">Watch Video</a>
                                        <?php endif; ?>
                                        <a href="view_blog.php?id=<?php echo $blog['id']; ?>" class="btn btn-info" style="background-color: #17a2b8; border: none;">Read More</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        No blogs available at the moment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- BLOG AREA END -->

<?php
include("./footer.php");
?>
