<?php  
include "connection.php";
include('pharmacist_header.php');
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ARZ Pharmacy</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Font Icons css -->
    <link rel="stylesheet" href="css/font-icons.css">
    <!-- plugins css -->
    <link rel="stylesheet" href="css/plugins.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        body {
          font-family: Arial, sans-serif;
          padding: 20px;
        }
    
        .form-group {
          margin-bottom: 15px;
        }
    
        label {
          display: block;
          font-weight: bold;
          margin-bottom: 5px;
        }
    
        input, textarea, select, #addProductButton {
          width: 100%;
          padding: 8px;
          font-size: 1em;
          border: 1px solid #ccc;
          border-radius: 4px;
        }
    
        #addProductButton {
          background-color: #0A9A73;
          color: white;
          cursor: pointer;
        }
    
        #addProductButton:hover {
          background-color: #0056b3;
        }
    
        .image-preview {
          margin-top: 10px;
          display: flex;
          flex-wrap: wrap;
          gap: 10px;
        }
    
        .image-preview img {
          width: 100px;
          height: 100px;
          object-fit: cover;
          border: 1px solid #ddd;
          border-radius: 4px;
        }
        .form-group1 {
        display: flex;
        align-items: center;
        gap: 20px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        label {
            margin: 0;
        }

      </style>
</head>

<body>
    <!-- SHOP DETAILS AREA START -->
    <div class="ltn__shop-details-area pb-85">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12">
                <div class="ltn__shop-details-inner mb-60 p-4" style="border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9;">
                    <h2 class="text-center mb-4" style="font-size: 28px; font-weight: bold; color: #333;">Add New Product</h2>
                    <form id="addProductForm" action="createNewProduct.php" method="post" enctype="multipart/form-data">
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="productName">Product Name:</label>
                            <input type="text" id="productName" name="productName" placeholder="Enter product name" required>
                        </div>

                        <!-- Original Price -->
                        <div class="form-group">
                            <label for="originalPrice">Original Price:</label>
                            <input type="number" id="originalPrice" name="originalPrice" placeholder="Enter original price" min="0" step="0.01" required>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" min="1" required>
                        </div><br>

                        <!-- Type -->
                        <div class="form-group1">
                            <label>Type:</label>
                            <div class="form-check">
                                <input type="radio" id="medicine" name="Type" value="medicine" 
                                     >
                                <label for="medicine">Medicine</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="cosmetic" name="Type" value="cosmetic" 
                                     >
                                <label for="cosmetic">Cosmetic</label>
                            </div>
                        </div><br>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" placeholder="Enter a detailed description of the product..." rows="4" style="resize: none;"></textarea>
                        </div>

                        
                        <!-- Symptoms -->
                        <div class="form-group1">
                            <label>Symptoms:</label>
                            <div class="form-check">
                                <input type="radio" id="headache" name="symptoms" value="headache" required>
                                <label for="headache">Headache</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="cough" name="symptoms" value="cough" required>
                                <label for="cough">Cough</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="fever" name="symptoms" value="fever" required>
                                <label for="fever">Fever</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="fatigue" name="symptoms" value="fatigue" required>
                                <label for="fatigue">Fatigue</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="other" name="symptoms" value="other" required>
                                <label for="other">Other</label>
                            </div>
                        </div><br>

                        <!-- Approval Type -->

                        <div class="form-group1">
                            <label>Approval Type:</label>
                            <div class="form-check">
                                <input type="radio" id="pharmacistCheck" name="approvalType" value="pharmacist check" required>
                                <label for="pharmacistCheck">Pharmacist Check</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="prescriptionNeeded" name="approvalType" value="prescription needed" required>
                                <label for="prescriptionNeeded">Prescription Needed</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="ministryHealthCheck" name="approvalType" value="ministry of health check" required>
                                <label for="ministryHealthCheck">Ministry of Health Check</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="noApprovalNeeded" name="approvalType" value="no approval needed" required>
                                <label for="noApprovalNeeded">No Approval Needed</label>
                            </div>
                        </div><br>

                        <!-- Product Images -->
                        <div class="form-group">
                            <label for="productImages">Product Image</label>
                            <input type="file" id="productImages" name="product_image[]" accept="image/*" multiple>
                            <div id="imagePreview" class="image-preview mt-2"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="addProductButton" class="btn btn-success w-100" style="font-size: 16px; font-weight: bold;">
                            Add Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- SHOP DETAILS AREA END -->
</div>
</body>
</html>

