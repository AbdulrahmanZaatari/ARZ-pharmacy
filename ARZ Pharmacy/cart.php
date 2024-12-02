<?php
session_start();
include("./role_based_header.php");
include "./connection.php";
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
} else {
    $UID = $_SESSION['user_id'];
}
$query="select * from approval_requests where customer_id='$UID' and status='approved'";
$result=mysqli_query($conn,$query);
while($row=mysqli_fetch_assoc($result)){
    $productId=$row['product_id'];
    $query2="select name,price,image_path from products where id='$productId'";
    $result2=mysqli_query($conn,$query2);
    $row2=mysqli_fetch_assoc($result2);
    $i_path = $row2['image_path'];
    $pname = $row2['name'];
    $pprice = $row2['price'];
    $insert="insert into cart values('$UID','$productId','$pprice','$pname','$i_path')";
    $result2=mysqli_query($conn,$insert);
}
$delete="delete from approval_requests where customer_id='$UID' and status='approved'";
$result=mysqli_query($conn,$delete);
?>

<div class="ltn__utilize-overlay"></div>

<!-- BREADCRUMB AREA START -->
<div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bs-bg="img/bg/14.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__breadcrumb-inner">
                    <h1 class="page-title">Cart</h1>
                    <div class="ltn__breadcrumb-list">
                        <ul>
                            <li><a href="index.php"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                            <li>Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BREADCRUMB AREA END -->

<!-- SHOPPING CART AREA START -->
<div class="liton__shoping-cart-area mb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping-cart-inner">
                    <form action="checkout.php" method="post" enctype="multipart/form-data">
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
                                <tbody>
                                    <?php 
                                    $fetch = "SELECT product_id, name, image_path, price FROM cart WHERE customer_id=$UID";
                                    $result = mysqli_query($conn, $fetch);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $image_path = $row['image_path'];
                                        $name = $row['name'];
                                        $price = $row['price'];
                                        $PID = $row['product_id'];
                                        echo "
                                        <tr>
                                            <td class='cart-product-remove'>
                                                <a href='removeProductFromCart.php?PID=$PID'>x</a>
                                            </td>
                                            <td class='cart-product-image'>
                                                <img src='./$image_path' alt='#'>
                                            </td>
                                            <td class='cart-product-info'>
                                                <h4>$name</h4>
                                            </td>
                                            <td class='cart-product-price price' data-price='$price'>$price</td>
                                            <td class='cart-product-quantity'>
                                                <div class='cart'>
                                                    <input type='hidden' name='product_ids[]' value='$PID'>
                                                    <input type='number' name='quantities[]' value='1' min='1' class='cart-plus-minus-box qty-input'>
                                                </div>
                                            </td>
                                            <td class='cart-product-subtotal total'>0.00</td>
                                        </tr>
                                        ";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="shoping-cart-total mt-50">
                            <h4>Cart Totals</h4>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="Order total">
                                            <strong>
                                                <input type="text" name="total" readonly id="orderTotalInput" />
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="btn-wrapper text-right">
                                <button type="submit" class="theme-btn-1 btn btn-effect-1">Proceed to checkout</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function update() {
    document.querySelectorAll("tr").forEach(row => {
        const priceElement = row.querySelector(".price");
        const qtyInput = row.querySelector(".qty-input");
        const totalElement = row.querySelector(".total");

        if (priceElement && qtyInput && totalElement) {
            const basePrice = parseFloat(priceElement.dataset.price);
            const count = parseInt(qtyInput.value) || 0;
            totalElement.textContent = (basePrice * count).toFixed(2);
        }
    });
    updateOrderTotal(); // Update order total whenever row totals are updated
}

function updateOrderTotal() {
    let total = 0;
    document.querySelectorAll(".total").forEach(subtotalElement => {
        const subtotal = parseFloat(subtotalElement.textContent) || 0;
        total += subtotal;
    });

    // Update the value of the order total input
    const orderTotalInput = document.getElementById("orderTotalInput");
    if (orderTotalInput) {
        orderTotalInput.value = total.toFixed(2);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Update totals on manual input change
    document.querySelectorAll(".qty-input").forEach(input => {
        input.addEventListener("input", update);
    });

    // Initial update
    update();
});
</script>

<?php
include("./footer.php");
?>
