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

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
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
        gap: 20px; /* Adjust spacing between each radio button group */
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

    

    

    <!-- MODAL AREA START (Quick View Modal) -->
    <div class="ltn__modal-area ltn__quick-view-modal-area">
        <div class="modal fade" id="quick_view_modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <!-- <i class="fas fa-times"></i> -->
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="modal-product-img">
                                            <img src="img/product/4.png" alt="#">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="modal-product-info">
                                            <div class="product-ratting">
                                                <ul>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    <li class="review-total"> <a href="#"> ( 95 Reviews )</a></li>
                                                </ul>
                                            </div>
                                            <h3>Digital Stethoscope</h3>
                                            <div class="product-price">
                                                <span>$149.00</span>
                                                <del>$165.00</del>
                                            </div>
                                            <div class="modal-product-meta ltn__product-details-menu-1">
                                                <ul>
                                                    <li>
                                                        <strong>Categories:</strong> 
                                                        <span>
                                                            <a href="#">Parts</a>
                                                            <a href="#">Car</a>
                                                            <a href="#">Seat</a>
                                                            <a href="#">Cover</a>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="ltn__product-details-menu-2">
                                                <ul>
                                                    <li>
                                                        <div class="cart-plus-minus">
                                                            <input type="text" value="02" name="qtybutton" class="cart-plus-minus-box">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="theme-btn-1 btn btn-effect-1" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#add_to_cart_modal">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            <span>ADD TO CART</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="ltn__product-details-menu-3">
                                                <ul>
                                                    <li>
                                                        <a href="#" class="" title="Wishlist" data-bs-toggle="modal" data-bs-target="#liton_wishlist_modal">
                                                            <i class="far fa-heart"></i>
                                                            <span>Add to Wishlist</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="" title="Compare" data-bs-toggle="modal" data-bs-target="#quick_view_modal">
                                                            <i class="fas fa-exchange-alt"></i>
                                                            <span>Compare</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <hr>
                                            <div class="ltn__social-media">
                                                <ul>
                                                    <li>Share:</li>
                                                    <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                                    <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                                    <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                                                    <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Add To Cart Modal) -->
    <div class="ltn__modal-area ltn__add-to-cart-modal-area">
        <div class="modal fade" id="add_to_cart_modal" tabindex="-1">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="modal-product-img">
                                            <img src="img/product/1.png" alt="#">
                                        </div>
                                         <div class="modal-product-info">
                                            <h5><a href="product-details.html">Digital Stethoscope</a></h5>
                                            <p class="added-cart"><i class="fa fa-check-circle"></i>  Successfully added to your Cart</p>
                                            <div class="btn-wrapper">
                                                <a href="cart.html" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                                                <a href="checkout.html" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                                            </div>
                                         </div>
                                         <!-- additional-info -->
                                         <div class="additional-info d-none">
                                            <p>We want to give you <b>10% discount</b> for your first order, <br>  Use discount code at checkout</p>
                                            <div class="payment-method">
                                                <img src="img/icons/payment.png" alt="#">
                                            </div>
                                         </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Wishlist Modal) -->
    <div class="ltn__modal-area ltn__add-to-cart-modal-area">
        <div class="modal fade" id="liton_wishlist_modal" tabindex="-1">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="modal-product-img">
                                            <img src="img/product/7.png" alt="#">
                                        </div>
                                         <div class="modal-product-info">
                                            <h5><a href="product-details.html">Digital Stethoscope</a></h5>
                                            <p class="added-cart"><i class="fa fa-check-circle"></i>  Successfully added to your Wishlist</p>
                                            <div class="btn-wrapper">
                                                <a href="wishlist.html" class="theme-btn-1 btn btn-effect-1">View Wishlist</a>
                                            </div>
                                         </div>
                                         <!-- additional-info -->
                                         <div class="additional-info d-none">
                                            <p>We want to give you <b>10% discount</b> for your first order, <br>  Use discount code at checkout</p>
                                            <div class="payment-method">
                                                <img src="img/icons/payment.png" alt="#">
                                            </div>
                                         </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>

    <!-- Add product -->
    
  
</body>
</html>

