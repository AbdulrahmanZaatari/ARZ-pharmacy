<?php 
include("./connection.php");

if (
    !empty($_POST['productName']) &&
    !empty($_POST['originalPrice']) &&
    !empty($_POST['quantity']) &&
    !empty($_POST['Type']) &&
    !empty($_POST['description']) &&
    !empty($_POST['symptoms']) &&
    !empty($_POST['approvalType']) &&
    isset($_FILES['product_image']) // Correctly check for uploaded files
) {
    $productName = $_POST['productName'];
    $originalPrice = $_POST['originalPrice'];
    $quantity = $_POST['quantity'];
    $page = $_POST['Type'];
    $description = $_POST['description'];
    $symptoms = $_POST['symptoms'];
    $approvalType = $_POST['approvalType'];

    $target_dir = "uploads/product_images/"; // Directory to save images
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }

    // Process each file in the upload
    $uploaded_files = [];
    foreach ($_FILES["product_image"]["name"] as $key => $filename) {
        $target_file = $target_dir . basename($filename);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $upload_ok = 1;

        // Validate the file is an image
        if (!empty($_FILES["product_image"]["tmp_name"][$key])) {
            $check = getimagesize($_FILES["product_image"]["tmp_name"][$key]);
            if ($check === false) {
                echo "File $filename is not an image.";
                $upload_ok = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file $filename already exists.";
            $upload_ok = 0;
        }

        // Allow only certain file formats
        $allowed_file_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($image_file_type, $allowed_file_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. File $filename skipped.";
            $upload_ok = 0;
        }

        if ($upload_ok) {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"][$key], $target_file)) {
                $uploaded_files[] = $target_file; // Save file path for database entry
            } else {
                echo "Error uploading file $filename.";
            }
        }
    }

    if (!empty($uploaded_files)) {
        $image_paths = implode(",", $uploaded_files); // Convert array to comma-separated string
        $query = "INSERT INTO products (name, description, symptoms, page, approval, price, quantity, image_path) 
                  VALUES ('$productName', '$description', '$symptoms', '$page', '$approvalType', '$originalPrice', '$quantity', '$image_paths')";
        if ($conn->query($query)) {
            echo "<script>
                    alert('Successfully Added');
                    window.location.href = 'addProduct.php';
                  </script>";
        } else {
            echo "Error saving product: " . $conn->error;
        }
    } else {
        echo "No valid files were uploaded.";
    }
} else {
    echo '<script>
        alert("Some fields are missing or invalid.");
    </script>';
}
?>
