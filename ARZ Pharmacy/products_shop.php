<?php
// Start the session to access session variables
session_start();

// Include the database connection file
include("./connection.php");

// Check if the user is logged in as a pharmacist
include("./role_based_header.php");
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Products Page</title>
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
</head>

<body>
<div class="ltn__product-details-menu-2">
    <ul>
        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'pharmacist') {
            echo '
            <li>
                <a href="addProduct.php" class="theme-btn-1 btn btn-effect-1" title="Add Products">
                    <i class="fas fa-plus-circle"></i>
                    <span>ADD PRODUCTS</span>
                </a>
            </li>';
        }
        ?>
    </ul>
</div>


    <div class="ltn__product-area ltn__product-gutter mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="ltn__shop-options">
                        <ul>
                            <li>
                            </li>
                            <li>
                               <div class="short-by text-center">
                               <form action="products_shop.php" method="POST">
                                    <select class="nice-select" name="sort1">
                                        <option>Sort by new arrivals</option>
                                        <option>Sort by price: low to high</option>
                                        <option>Sort by price: high to low</option>
                                    </select>
                                    <button type="submit" name="Form1">Filter</button>
                                </form>
                                </div> 
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_product_grid">
                            <div class="ltn__product-tab-content-inner ltn__product-grid-view">
                                <div class="row">
                                    <?php
                                    $page = "medicine";
                                    if(isset($_POST['Form1'])){
                                        $filter = $_POST['sort1'];
                                        $sql="";
                                        if($filter=='Sort by new arrivals'){
                                            $sql.= "select * from products where page = '$page' order by 1 DESC ";
                                        }else if($filter== "Sort by price: low to high"){
                                            $sql.= "select * from products where page = '$page'  order by 7 ";
                                        }else {$sql.= "select * from products where page = '$page'  order by 7 DESC ";}
                                        $stmt=$conn->prepare($sql);
                                        $stmt->execute();
                                        $result=$stmt->get_result();
                                        while ($row = $result->fetch_assoc()){
                                            $PID= $row["id"];
                                            $productName=$row['name'];
                                            $productPrice=$row['price'];
                                            $productImage=$row['image_path'];
                                            
                                    //         echo '
                                    //     <!-- ltn__product-item -->
                                    // <div class="col-xl-4 col-sm-6 col-6">
                                    //     <div class="ltn__product-item ltn__product-item-3 text-center">
                                    //         <div class="product-img">
                                    //             <a href="product-details.php?PID='.$PID.'"><img src="./'.$productImage.'" alt="#" width="220" height="230"></a>
                                                
                                    //         </div>
                                    //         <div class="product-info">
                                                
                                    //             ';
                                    //             echo '
                                    //             <h2 class="product-title"><a href="product-details.php?PID='.$PID.'">'.$productName.'</a></h2>
                                    //             <div class="product-price">
                                    //                 <span>$'.$productPrice.'</span>';
                                    //             echo' 
                                    //             </div>
                                    //         </div>
                                    //     </div>
                                    // </div> 
                                    // ';
                                        }  
                                        //search
                                    }else if(isset($_POST['search1'])){
                                        $searchW = $_POST['wordd'];
                                        $sql = "SELECT * FROM products WHERE name LIKE '" .$searchW. "%' and page = '$page'  ORDER BY name";
                                    }else if(isset($_POST['headache'])){
                                    $sql = "SELECT * FROM products WHERE symptoms = 'headache' and page = '$page'";
                                    }
                                    else if(isset($_POST['cough'])) {
                                        $sql = "SELECT * FROM products WHERE symptoms = 'cough' and page = '$page'";
                                    }
                                    else if(isset($_POST['fever'])) {
                                        $sql = "SELECT * FROM products WHERE symptoms = 'fever' and page = '$page'";
                                    } 
                                    else if(isset($_POST['fatigue'])) {
                                        $sql = "SELECT * FROM products WHERE symptoms = 'fatigue' and page = '$page'";
                                    } 
                                    else if(isset($_POST['nausea'])) {
                                        $sql = "SELECT * FROM products WHERE symptoms = 'nausea' and page = '$page'";
                                    }
                                    else if(isset($_POST['noApprovalNeeded'])) {
                                        $sql = "SELECT * FROM products WHERE approval = 'no approval needed' and page = '$page'";
                                    }
                                    else if(isset($_POST['pharmacistCheck'])) {
                                        $sql = "SELECT * FROM products WHERE approval = 'pharmacist check' and page = '$page'";
                                    }
                                    else if(isset($_POST['prescriptionNeeded'])) {
                                        $sql = "SELECT * FROM products WHERE approval = 'prescription needed' and page = '$page'";
                                    }
                                    else if(isset($_POST['ministryOfHealthCheck'])) {
                                        $sql = "SELECT * FROM products WHERE approval = 'ministry of health check' and page = '$page'";
                                    }
                                    else{
                                        $sql = "SELECT * FROM products where page = '$page' ORDER BY id DESC LIMIT 9";
                                    }
                                    $result=mysqli_query($conn,$sql);
    
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $PID = $row["id"];
                                        $productName = $row['name'];
                                        $productPrice = $row['price'];
                                        $productImage = $row['image_path'];
                                        
                                        echo '
                                    <!-- ltn__product-item -->
                                <div class="col-xl-4 col-sm-6 col-6">
                                    <div class="ltn__product-item ltn__product-item-3 text-center">
                                        <div class="product-img">
                                            <a href="product-details.php?PID='.$PID.'"><img src="./'.$productImage.'" alt="#" width="220" height="230"></a>
                                            
                                        </div>
                                        <div class="product-info">
                                            
                                            ';
                                            echo '
                                            <h2 class="product-title"><a href="product-details.php?PID='.$PID.'">'.$productName.'</a></h2>
                                            <div class="product-price">
                                                <span>$'.$productPrice.'</span>';
                                            echo' 
                                            </div>
                                        </div>  
                                    </div>
                                </div> 
                                ';
                                    }            
                    ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="ltn__pagination-area text-center">
                        <div class="ltn__pagination">
                            <ul>
                                <li><a href="#"><i class="fas fa-angle-double-left"></i></a></li>
          
                                <li class="active"><a href="#">1</a></li>
                                
                                <li><a href="#"><i class="fas fa-angle-double-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="sidebar ltn__shop-sidebar ltn__right-sidebar">
                        
                        <!-- Search Widget -->
                        <div class="widget ltn__search-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Search Products</h4>
                            <form action="products_shop.php" method="POST">
                                <input type="text" name="wordd" placeholder="Search your keyword...">
                                <button type="submit" name="search1"><i class="fas fa-search"></i></button>
                            </form>
                        </div>

                        <!-- Symptoms Widget -->
                        <div class="widget ltn__tagcloud-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Types</h4>
                            <ul>
                                <form action="products_shop.php" method="POST">
                                <button class="btn btn-light" type="submit" name="fever">FEVER</button>
                                <button class="btn btn-light" type="submit" name="cough">COUGH</button><br><br>
                                <button class="btn btn-light" type="submit" name="headache">HEADACHE</button><br><br>
                                <button class="btn btn-light" type="submit" name="fatigue">FATIGUE</button><br><br>
                                </form>
                            </ul>
                        </div>
                        <!-- Approval Widget -->
                        <div class="widget ltn__tagcloud-widget ltn__size-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Approval Types</h4>
                            <ul>
                            <form action="products_shop.php" method="POST">
                                <button class="btn btn-light" type="submit" name="noApprovalNeeded">No Approval</button><br><br>
                                <button class="btn btn-light" type="submit" name="pharmacistCheck">Pharmacist Check</button><br><br>
                                <button class="btn btn-light" type="submit" name="prescriptionNeeded">Prescription needed</button><br><br>
                                <button class="btn btn-light" type="submit" name="ministryOfHealthCheck">Ministry of Health Check</button><br><br>
                                </form>
                            </ul>
                        </div>
                        

                    </aside>
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->
    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
</body>
</html>

