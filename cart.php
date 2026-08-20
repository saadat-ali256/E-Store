<?php

require("includes/common.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];


/* =====================================================
   GET CART PRODUCTS
===================================================== */

$query = "
    SELECT
        users_items.id AS cart_id,
        products.id AS product_id,
        products.name AS product_name,
        products.price AS product_price,
        products.image AS product_image
    FROM users_items

    INNER JOIN products
        ON users_items.item_id = products.id

    WHERE users_items.user_id = $user_id
    AND users_items.status = 'Added to cart'

    ORDER BY users_items.id DESC
";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Cart error: " . mysqli_error($con));
}


/* =====================================================
   GROUP SAME PRODUCTS
===================================================== */

$cart = array();
$total = 0;
$total_items = 0;

while ($row = mysqli_fetch_assoc($result)) {

    $product_id = (int) $row['product_id'];

    if (!isset($cart[$product_id])) {

        $cart[$product_id] = array(
            'product_id'    => $product_id,
            'product_name'  => $row['product_name'],
            'product_price' => (float) $row['product_price'],
            'product_image' => $row['product_image'],
            'quantity'      => 0,
            'cart_ids'      => array()
        );
    }

    $cart[$product_id]['quantity']++;

    $cart[$product_id]['cart_ids'][] =
        (int) $row['cart_id'];
}


/* =====================================================
   CALCULATE TOTAL
===================================================== */

foreach ($cart as $item) {

    $subtotal =
        $item['product_price'] *
        $item['quantity'];

    $total += $subtotal;

    $total_items += $item['quantity'];
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>E-Store | Shopping Bag</title>


<link rel="stylesheet"
      href="bootstrap/css/bootstrap.min.css">

<link rel="stylesheet"
      href="css/font-awesome/css/font-awesome.min.css">

<script src="bootstrap/js/jquery-3.5.1.min.js"></script>

<script src="bootstrap/js/bootstrap.min.js"></script>


<style>

/* =====================================================
   GLOBAL
===================================================== */

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {

    margin: 0;

    padding-top: 52px;

    background: #f5f5f7;

    color: #1d1d1f;

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;
}


/* =====================================================
   NAVBAR
===================================================== */

.store-nav {

    position: fixed;

    top: 0;
    left: 0;

    width: 100%;

    height: 52px;

    background:
        rgba(250,250,252,.94);

    backdrop-filter: blur(20px);

    -webkit-backdrop-filter: blur(20px);

    border-bottom:
        1px solid rgba(0,0,0,.08);

    z-index: 9999;
}


.nav-inner {

    max-width: 1100px;

    height: 52px;

    margin: auto;

    padding:
        0 20px;

    display: flex;

    align-items: center;

    justify-content: space-between;
}


.brand {

    color: #111;

    font-size: 19px;

    font-weight: 700;

    text-decoration: none;

    letter-spacing: -.5px;
}


.brand:hover {

    color: #111;

    text-decoration: none;
}


.nav-links {

    display: flex;

    align-items: center;

    gap: 30px;
}


.nav-links a {

    color: #222;

    font-size: 12px;

    text-decoration: none;
}


.nav-links a:hover {

    color: #777;

}


.cart-badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 18px;

    height: 18px;

    padding: 0 5px;

    margin-left: 3px;

    background: #0071e3;

    color: white;

    border-radius: 20px;

    font-size: 9px;

    font-weight: 600;
}


/* =====================================================
   PAGE HEADER
===================================================== */

.page-header-cart {

    background: #fff;

    text-align: center;

    padding:
        65px 20px 45px;

    border-bottom:
        1px solid #e5e5e7;
}


.page-header-cart h1 {

    margin: 0;

    font-size: 48px;

    font-weight: 700;

    letter-spacing: -2px;
}


.page-header-cart p {

    margin:
        12px 0 0;

    color: #6e6e73;

    font-size: 17px;
}


/* =====================================================
   MAIN CART
===================================================== */

.cart-wrapper {

    max-width: 1100px;

    margin:
        40px auto 80px;

    padding:
        0 20px;
}


/* =====================================================
   CART TOP
===================================================== */

.cart-top {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;
}


.cart-top h2 {

    margin: 0;

    font-size: 25px;

    font-weight: 700;
}


.item-count {

    color: #6e6e73;

    font-size: 14px;
}


/* =====================================================
   CART CARD
===================================================== */

.cart-card {

    background: #fff;

    border-radius: 20px;

    border:
        1px solid #e5e5e7;

    overflow: hidden;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,.04);
}


/* =====================================================
   PRODUCT ROW
===================================================== */

.cart-product {

    display: flex;

    align-items: center;

    padding: 25px;

    border-bottom:
        1px solid #e5e5e7;
}


.cart-product:last-child {

    border-bottom: none;
}


/* =====================================================
   IMAGE
===================================================== */

.cart-product-image {

    width: 120px;

    height: 120px;

    flex-shrink: 0;

    background: #f8f8fa;

    border-radius: 15px;

    padding: 12px;

    display: flex;

    align-items: center;

    justify-content: center;
}


.cart-product-image img {

    width: 100%;

    height: 100%;

    object-fit: contain;
}


/* =====================================================
   PRODUCT INFO
===================================================== */

.cart-product-info {

    flex: 1;

    padding:
        0 25px;
}


.cart-product-info h3 {

    margin:
        0 0 8px;

    font-size: 19px;

    font-weight: 700;
}


.cart-product-info p {

    margin: 0;

    color: #6e6e73;

    font-size: 13px;
}


/* =====================================================
   PRICE
===================================================== */

.cart-price {

    font-size: 17px;

    font-weight: 600;

    min-width: 130px;

    text-align: right;
}


.cart-subtotal {

    color: #6e6e73;

    font-size: 12px;

    margin-top: 5px;
}


/* =====================================================
   REMOVE
===================================================== */

.remove-area {

    margin-left: 20px;

    min-width: 80px;

    text-align: right;
}


.remove-button {

    border: none;

    background: transparent;

    color: #ff3b30;

    font-size: 13px;

    padding: 5px;

    cursor: pointer;
}


.remove-button:hover {

    color: #d70015;

    text-decoration: underline;
}


/* =====================================================
   SUMMARY
===================================================== */

.summary-wrapper {

    margin-top: 25px;

    display: flex;

    justify-content: flex-end;
}


.summary-card {

    width: 390px;

    background: #fff;

    border:
        1px solid #e5e5e7;

    border-radius: 20px;

    padding: 28px;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,.04);
}


.summary-card h3 {

    margin:
        0 0 25px;

    font-size: 23px;

    font-weight: 700;
}


.summary-row {

    display: flex;

    justify-content: space-between;

    padding:
        10px 0;

    color: #6e6e73;

    font-size: 14px;
}


.summary-row.total {

    border-top:
        1px solid #e5e5e7;

    margin-top: 10px;

    padding-top: 18px;

    color: #1d1d1f;

    font-size: 20px;

    font-weight: 700;
}


.checkout-button {

    display: block;

    width: 100%;

    margin-top: 22px;

    padding:
        13px;

    border: none;

    border-radius: 25px;

    background: #0071e3;

    color: #fff;

    text-align: center;

    font-size: 15px;

    font-weight: 600;

    text-decoration: none;

    transition: .2s;
}


.checkout-button:hover {

    background: #0077ed;

    color: #fff;

    text-decoration: none;

    transform: translateY(-1px);
}


.continue-button {

    display: block;

    text-align: center;

    margin-top: 15px;

    color: #0071e3;

    font-size: 14px;

    text-decoration: none;
}


.continue-button:hover {

    color: #0077ed;

    text-decoration: none;
}


/* =====================================================
   EMPTY CART
===================================================== */

.empty-cart {

    max-width: 650px;

    margin:
        70px auto 100px;

    background: #fff;

    border-radius: 25px;

    padding:
        65px 30px;

    text-align: center;

    border:
        1px solid #e5e5e7;

    box-shadow:
        0 10px 30px
        rgba(0,0,0,.05);
}


.empty-cart-icon {

    width: 80px;

    height: 80px;

    margin:
        0 auto 25px;

    border-radius: 50%;

    background: #f5f5f7;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 32px;

    color: #6e6e73;
}


.empty-cart h2 {

    font-size: 30px;

    font-weight: 700;

    margin: 0;
}


.empty-cart p {

    color: #6e6e73;

    margin:
        12px 0 25px;
}


.shop-button {

    display: inline-block;

    background: #0071e3;

    color: #fff;

    padding:
        12px 25px;

    border-radius: 25px;

    font-size: 14px;

    font-weight: 600;

    text-decoration: none;
}


.shop-button:hover {

    background: #0077ed;

    color: #fff;

    text-decoration: none;
}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    background: #fff;

    border-top:
        1px solid #e5e5e7;

    padding:
        45px 20px 25px;

    color: #6e6e73;
}


.footer-brand {

    color: #1d1d1f;

    font-size: 20px;

    font-weight: 700;
}


.footer-bottom {

    border-top:
        1px solid #e5e5e7;

    margin-top: 30px;

    padding-top: 20px;

    font-size: 11px;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:767px) {

    .nav-inner {

        padding:
            0 15px;
    }


    .nav-links {

        gap: 18px;
    }


    .nav-links a:not(:last-child) {

        display: none;
    }


    .page-header-cart {

        padding:
            50px 15px 35px;
    }


    .page-header-cart h1 {

        font-size: 38px;

        letter-spacing: -1.5px;
    }


    .page-header-cart p {

        font-size: 15px;
    }


    .cart-wrapper {

        margin-top: 25px;

        padding:
            0 12px;
    }


    .cart-top h2 {

        font-size: 21px;
    }


    .cart-product {

        padding: 18px;

        flex-wrap: wrap;
    }


    .cart-product-image {

        width: 90px;

        height: 90px;
    }


    .cart-product-info {

        padding:
            0 0 0 15px;

        width:
            calc(100% - 90px);
    }


    .cart-product-info h3 {

        font-size: 16px;
    }


    .cart-price {

        width: 100%;

        text-align: left;

        margin-top: 15px;

        padding-left: 105px;
    }


    .remove-area {

        position: absolute;

        right: 20px;

        margin-top: 0;
    }


    .summary-wrapper {

        display: block;
    }


    .summary-card {

        width: 100%;
    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:420px) {

    .page-header-cart h1 {

        font-size: 34px;
    }


    .cart-product-info h3 {

        font-size: 15px;
    }


    .summary-card {

        padding: 22px;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="store-nav">

<div class="nav-inner">


<a
    href="products.php"
    class="brand">

E-Store

</a>


<div class="nav-links">


<a href="products.php">
Store
</a>


<a href="products.php#products">
Products
</a>


<a href="products.php#categories">
Categories
</a>


<a href="products.php#services">
Support
</a>


<a href="products.php#search">

<i class="fa fa-search"></i>

</a>


<a href="cart.php">

<i class="fa fa-shopping-bag"></i>

<span class="cart-badge">

<?php echo $total_items; ?>

</span>

</a>


</div>

</div>

</nav>


<!-- =====================================================
     PAGE HEADER
===================================================== -->

<section class="page-header-cart">

<h1>
Your Shopping Bag
</h1>

<p>
Review your products before checkout.
</p>

</section>


<?php if (!empty($cart)): ?>


<!-- =====================================================
     CART
===================================================== -->

<div class="cart-wrapper">


<div class="cart-top">

<h2>
Shopping Bag
</h2>

<div class="item-count">

<?php echo $total_items; ?>

item<?php echo ($total_items != 1 ? 's' : ''); ?>

</div>

</div>


<div class="cart-card">


<?php foreach ($cart as $item): ?>


<?php

$subtotal =
    $item['product_price'] *
    $item['quantity'];

$first_cart_id =
    $item['cart_ids'][0];

?>


<div class="cart-product">


<!-- IMAGE -->

<div class="cart-product-image">

<img
    src="img/<?php
        echo htmlspecialchars(
            $item['product_image']
        );
    ?>"
    alt="<?php
        echo htmlspecialchars(
            $item['product_name']
        );
    ?>">

</div>


<!-- INFO -->

<div class="cart-product-info">

<h3>

<?php
echo htmlspecialchars(
    $item['product_name']
);
?>

</h3>


<p>

Quantity:
<strong>

<?php
echo $item['quantity'];
?>

</strong>

</p>

</div>


<!-- PRICE -->

<div class="cart-price">

Rs.
<?php
echo number_format(
    $item['product_price'],
    2
);
?>

<div class="cart-subtotal">

Subtotal:
Rs.
<?php
echo number_format(
    $subtotal,
    2
);
?>

</div>

</div>


<!-- REMOVE -->

<div class="remove-area">

<a
    href="cart-remove.php?id=<?php
        echo $first_cart_id;
    ?>"
    class="remove-button"

    onclick="
        return confirm(
            'Remove this product from your cart?'
        );
    ">

<i class="fa fa-trash"></i>

Remove

</a>

</div>


</div>


<?php endforeach; ?>


</div>


<!-- =====================================================
     SUMMARY
===================================================== -->

<div class="summary-wrapper">


<div class="summary-card">


<h3>
Order Summary
</h3>


<div class="summary-row">

<span>
Items
</span>

<span>
<?php echo $total_items; ?>
</span>

</div>


<div class="summary-row">

<span>
Subtotal
</span>

<span>

Rs.
<?php
echo number_format(
    $total,
    2
);
?>

</span>

</div>


<div class="summary-row">

<span>
Delivery
</span>

<span>
Free
</span>

</div>


<div class="summary-row total">

<span>
Total
</span>

<span>

Rs.
<?php
echo number_format(
    $total,
    2
);
?>

</span>

</div>


<a
    href="checkout.php"
    class="checkout-button">

<i class="fa fa-lock"></i>

&nbsp;

Proceed to Checkout

</a>


<a
    href="products.php"
    class="continue-button">

<i class="fa fa-arrow-left"></i>

&nbsp;

Continue Shopping

</a>


</div>

</div>


</div>


<?php else: ?>


<!-- =====================================================
     EMPTY CART
===================================================== -->

<div class="empty-cart">


<div class="empty-cart-icon">

<i class="fa fa-shopping-bag"></i>

</div>


<h2>
Your bag is empty.
</h2>


<p>
Add something you love and it will appear here.
</p>


<a
    href="products.php"
    class="shop-button">

<i class="fa fa-shopping-bag"></i>

&nbsp;

Continue Shopping

</a>


</div>


<?php endif; ?>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">

<div class="container">


<div class="row">


<div class="col-sm-6">

<div class="footer-brand">
E-Store
</div>

<p>
Premium products. Simple shopping.
</p>

</div>


<div class="col-sm-3">

<strong>
Shop
</strong>

<br><br>

<a href="products.php">
Products
</a>

<br>

<a href="products.php#categories">
Categories
</a>

</div>


<div class="col-sm-3">

<strong>
Support
</strong>

<br><br>

<a href="products.php#services">
Customer Support
</a>

<br>

<a href="cart.php">
Shopping Bag
</a>

</div>


</div>


<div class="footer-bottom">

© <?php echo date('Y'); ?>

E-Store. All rights reserved.

</div>


</div>

</footer>


</body>

</html>