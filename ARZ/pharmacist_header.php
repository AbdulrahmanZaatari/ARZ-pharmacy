<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // Report all errors except notices and warnings
ini_set('display_errors', '0'); // Do not display errors
session_start();
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Pharmacy Header</title>
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
</head>

<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

<!-- Body main wrapper start -->
<div class="body-wrapper">

    <!-- HEADER AREA START -->
    <header class="ltn__header-area ltn__header-3">       
        <!-- ltn__header-top-area start -->
        <div class="ltn__header-top-area border-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="ltn__top-bar-menu">
                            <ul>
                                <li><a href="mailto:info@webmail.com?Subject=Flower%20greetings%20to%20you"><i class="icon-mail"></i> PharmacyARZ@gmail.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="top-bar-right text-right text-end">
                            <div class="ltn__top-bar-menu">
                                <ul>
                                    <li>
                                        <!-- ltn__social-media -->
                                        <div class="ltn__social-media">
                                            <ul>
                                                <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                                <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                                <li><a href="#" title="Dribbble"><i class="fab fa-dribbble"></i></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__header-top-area end --> 
        <!-- ltn__header-middle-area start -->
        <div class="ltn__header-middle-area">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="site-logo">
                            <a href="index.php"><img src="img/arz.png" alt="Logo"></a>
                        </div>
                    </div>
                    <div class="col header-contact-serarch-column d-none d-lg-block">
                        <div class="header-contact-search">
                            <!-- header-feature-item -->
                            <div class="header-feature-item">
                                <div class="header-feature-icon">
                                    <i class="icon-call"></i>
                                </div>
                                <div class="header-feature-info">
                                    <h6>Phone</h6>
                                    <p><a href="tel:+96181906611">+961-81562376</a></p>
                                </div>
                            </div>
                            <!-- header-search-2 -->
                            <div class="header-search-2">
                                <form id="#123" method="get" action="#">
                                    <input type="text" name="search" value="" placeholder="Search here..."/>
                                    <button type="submit">
                                        <span><i class="icon-search"></i></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <!-- header-options -->
                        <div class="ltn__header-options">
                            <ul>
                                <?php if (!isset($_SESSION['pharmacist_email'])): ?>
                                <!-- Show Cart for Customers -->
                                <li>
                                    <!-- mini-cart 2 -->
                                    <div class="mini-cart-icon mini-cart-icon-2">
                                        <a href="#ltn__utilize-cart-menu" class="ltn__utilize-toggle">
                                            <span class="mini-cart-icon">
                                                <i class="icon-shopping-cart"></i>
                                                <sup>
                                                    <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?>
                                                </sup>
                                            </span>
                                            <h6>
                                                <span>Your Cart</span>
                                                <span class="ltn__secondary-color">
                                                    <?php echo isset($_SESSION['cart_total']) ? "$" . $_SESSION['cart_total'] : "$0.00"; ?>
                                                </span>
                                            </h6>
                                        </a>
                                    </div>
                                </li>
                                <?php endif; ?>
                                <!-- User Menu -->
                                <li class="d-none---"> 
                                    <div class="ltn__drop-menu user-menu">
                                        <ul>
                                            <li>
                                                <a href="#"><i class="icon-user"></i></a>
                                                <ul style="width: 320px;">
                                                        <li><a href="pharmacist_account.php">My Account</a></li>
                                                        <li><a href="stock.php">Stock Records</a></li>
                                                        <li><a href="logout.php">Logout</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__header-middle-area end -->
        <!-- header-bottom-area start -->
        <div class="header-bottom-area ltn__border-top ltn__header-sticky  ltn__sticky-bg-white--- ltn__sticky-bg-secondary ltn__secondary-bg section-bg-1 menu-color-white d-none d-lg-block">
            <div class="container">
            <style>
                    #google_translate_element {
                        display: none; /* Initially hide the element */
                        margin-left: 15px;
                    }

                    #translate-button {
                        margin-left: 15px;
                        padding: 5px 10px;
                        background-color: #4CAF50;
                        color: white;
                        border: none;
                        cursor: pointer;
                    }
                </style>

                <div id="google_translate_element" style="display: none; margin-left: 15px;"></div>
                <button id="translate-button" style="margin-left: 15px; padding: 5px 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">
                    Translate
                </button>

                <script type="text/javascript">
                    // Initialize the Google Translate element
                    function googleTranslateElementInit() {
                        new google.translate.TranslateElement({ pageLanguage: 'en' }, 'google_translate_element');

                        // Ensure the translate element is hidden after initialization
                        document.getElementById('google_translate_element').style.display = 'none';
                    }

                    // Toggle the visibility of the translate element on button click
                    document.getElementById("translate-button").addEventListener("click", function () {
                        const translateElement = document.getElementById("google_translate_element");
                        if (translateElement.style.display === "none" || translateElement.style.display === "") {
                            translateElement.style.display = "inline-block";
                        } else {
                            translateElement.style.display = "none";
                        }
                    });
                </script>

                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                <div class="row">
                    <div class="col header-menu-column justify-content-center">
                        <div class="sticky-logo">
                            <div class="site-logo">
                            </div>
                        </div>
                        <div class="header-menu header-menu-2">
                            <nav>
                                <div class="ltn__main-menu">
                                    <ul>
                                        <li><a href="index.php">Home</a></li>
                                        <li><a href="service.php">About</a></li>
                                        <li><a href="shop.php">Cosmetics</a></li>
                                        <li><a href="products_shop.php">Products</a></li>
                                        <li><a href="publicQ&A.php">Q&A</a></li>
                                        <li><a href="blog.php">Blogs</a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
        <style>
            #google_translate_element {
            display: inline-block;
            margin-left: 15px;
        }

        #translate-button {
            margin-left: 15px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        </style>
                    </div>
                </div>
            </div>
        </div>
        <!-- header-bottom-area end -->
    </header>
    <!-- HEADER AREA END -->

    <!-- MOBILE MENU START -->
    <div class="mobile-header-menu-fullwidth mb-30 d-block d-lg-none">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Mobile Menu Button -->
                    <div class="mobile-menu-toggle d-lg-none">
                        <span>MENU</span>
                        <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                            <svg viewBox="0 0 800 600">
                                <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MOBILE MENU END -->
</div>
</body>
</html>
