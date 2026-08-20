<?php

require("includes/common.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =====================================================
   LOGIN CHECK
===================================================== */

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


/* =====================================================
   PRODUCT ID
===================================================== */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];


/* =====================================================
   GET PRODUCT
===================================================== */

$query = "
    SELECT id, name, description, price, image
    FROM products
    WHERE id = $product_id
    LIMIT 1
";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Product error: " . mysqli_error($con));
}


if (mysqli_num_rows($result) == 0) {
    header("Location: products.php");
    exit();
}


$product = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
<?php echo htmlspecialchars($product['name']); ?> | E-Store
</title>


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

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Roboto,
        Arial,
        sans-serif;

    background: #f5f5f7;

    color: #1d1d1f;
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

    padding: 0 20px;

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

    transition: .2s;
}


.nav-links a:hover {

    color: #777;
}


.cart-count {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 17px;

    height: 17px;

    padding: 0 5px;

    background: #0071e3;

    color: #fff;

    border-radius: 20px;

    font-size: 9px;

    margin-left: 2px;
}


/* =====================================================
   DETAILS PAGE
===================================================== */

.details-wrapper {

    min-height: calc(100vh - 52px);

    padding: 60px 20px 80px;

    display: flex;

    align-items: center;
}


/* =====================================================
   BACK BUTTON
===================================================== */

.back-area {

    max-width: 1100px;

    width: 100%;

    margin: 0 auto 20px;
}


.back-button {

    color: #0071e3;

    font-size: 14px;

    text-decoration: none;
}


.back-button:hover {

    color: #005bb5;

    text-decoration: none;
}


/* =====================================================
   PRODUCT CARD
===================================================== */

.details-card {

    max-width: 1100px;

    width: 100%;

    margin: auto;

    background: #fff;

    border-radius: 25px;

    overflow: hidden;

    border: 1px solid #e5e5e7;

    box-shadow:
        0 20px 60px
        rgba(0,0,0,.09);

    animation:
        detailsAppear
        .6s
        ease;
}


@keyframes detailsAppear {

    from {

        opacity: 0;

        transform:
            translateY(25px);

    }

    to {

        opacity: 1;

        transform:
            translateY(0);

    }

}


/* =====================================================
   IMAGE SIDE
===================================================== */

.product-image-side {

    min-height: 600px;

    background: #f5f5f7;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 55px;
}


.product-main-image {

    width: 100%;

    max-width: 500px;

    height: 500px;

    object-fit: contain;

    transition:
        transform .4s ease;

    filter:
        drop-shadow(
            0 25px 30px
            rgba(0,0,0,.13)
        );
}


.product-main-image:hover {

    transform:
        scale(1.05)
        translateY(-5px);
}


/* =====================================================
   INFO SIDE
===================================================== */

.product-info-side {

    min-height: 600px;

    padding: 60px 55px;

    display: flex;

    flex-direction: column;

    justify-content: center;
}


.product-label {

    display: inline-block;

    width: fit-content;

    background: #f0f7ff;

    color: #0071e3;

    padding: 6px 12px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 700;

    margin-bottom: 18px;
}


.product-title {

    font-size: 43px;

    line-height: 1.08;

    letter-spacing: -2px;

    font-weight: 700;

    margin: 0 0 18px;
}


.product-description {

    color: #6e6e73;

    font-size: 16px;

    line-height: 1.7;

    margin-bottom: 25px;
}


/* =====================================================
   PRICE
===================================================== */

.product-price {

    font-size: 30px;

    font-weight: 700;

    color: #1d1d1f;

    margin-bottom: 25px;
}


/* =====================================================
   PRODUCT FEATURES
===================================================== */

.product-features {

    border-top:
        1px solid #e5e5e7;

    border-bottom:
        1px solid #e5e5e7;

    padding: 20px 0;

    margin-bottom: 25px;
}


.feature-line {

    display: flex;

    align-items: center;

    margin-bottom: 13px;

    color: #555;

    font-size: 13px;
}


.feature-line:last-child {

    margin-bottom: 0;
}


.feature-line i {

    width: 28px;

    color: #0071e3;

    font-size: 16px;
}


/* =====================================================
   ADD TO CART
===================================================== */

.detail-add-button {

    width: 100%;

    height: 54px;

    border: none;

    border-radius: 28px;

    background: #0071e3;

    color: white;

    font-size: 16px;

    font-weight: 600;

    transition:
        all .25s ease;
}


.detail-add-button:hover {

    background: #005bb5;

    transform:
        translateY(-2px);

    box-shadow:
        0 10px 25px
        rgba(0,113,227,.25);
}


.detail-add-button:disabled {

    opacity: .75;

    cursor: not-allowed;

    transform: none;
}


/* =====================================================
   CONTINUE SHOPPING
===================================================== */

.continue-shopping {

    display: block;

    text-align: center;

    margin-top: 15px;

    color: #0071e3;

    font-size: 13px;

    text-decoration: none;
}


.continue-shopping:hover {

    color: #005bb5;

    text-decoration: none;
}


/* =====================================================
   TOAST
===================================================== */

.toast-box {

    display: none;

    position: fixed;

    right: 25px;

    bottom: 25px;

    z-index: 99999;

    background: #1d1d1f;

    color: #fff;

    padding:
        15px 20px;

    border-radius: 13px;

    box-shadow:
        0 10px 35px
        rgba(0,0,0,.25);

    font-size: 13px;
}


.toast-box i {

    color: #4cd964;

    margin-right: 7px;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 767px) {

    .details-wrapper {

        padding:
            30px 12px 50px;

        display: block;
    }


    .product-image-side {

        min-height: 350px;

        padding: 30px;
    }


    .product-main-image {

        height: 320px;
    }


    .product-info-side {

        min-height: auto;

        padding:
            35px 25px;
    }


    .product-title {

        font-size: 32px;

        letter-spacing: -1.2px;
    }


    .product-description {

        font-size: 14px;
    }


    .product-price {

        font-size: 26px;
    }


    .nav-links {

        gap: 17px;
    }


    .nav-links a:not(:last-child) {

        display: none;
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


<a href="products.php"
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


<a href="cart.php">

<i class="fa fa-shopping-bag"></i>

<span
    class="cart-count"
    id="cartCount">

0

</span>

</a>


</div>

</div>

</nav>


<!-- =====================================================
     PRODUCT DETAILS
===================================================== -->

<div class="details-wrapper">


<div style="width:100%;">


<!-- BACK -->

<div class="back-area">

<a href="products.php"
   class="back-button">

<i class="fa fa-angle-left"></i>

&nbsp; Back to Products

</a>

</div>


<!-- CARD -->

<div class="details-card">


<div class="row"
     style="margin:0;">


<!-- =================================================
     IMAGE
================================================= -->

<div class="col-md-6"
     style="padding:0;">


<div class="product-image-side">


<img
    src="img/<?php
        echo htmlspecialchars(
            $product['image'],
            ENT_QUOTES,
            'UTF-8'
        );
    ?>"
    class="product-main-image"
    alt="<?php
        echo htmlspecialchars(
            $product['name'],
            ENT_QUOTES,
            'UTF-8'
        );
    ?>">


</div>

</div>


<!-- =================================================
     INFORMATION
================================================= -->

<div class="col-md-6"
     style="padding:0;">


<div class="product-info-side">


<div class="product-label">

<i class="fa fa-check-circle"></i>

&nbsp; Available

</div>


<h1 class="product-title">

<?php

echo htmlspecialchars(
    $product['name'],
    ENT_QUOTES,
    'UTF-8'
);

?>

</h1>


<div class="product-description">

<?php

echo nl2br(
    htmlspecialchars(
        $product['description'],
        ENT_QUOTES,
        'UTF-8'
    )
);

?>

</div>


<div class="product-price">

Rs.

<?php

echo number_format(
    $product['price'],
    2
);

?>

</div>


<!-- FEATURES -->

<div class="product-features">


<div class="feature-line">

<i class="fa fa-shield"></i>

Secure Shopping

</div>


<div class="feature-line">

<i class="fa fa-truck"></i>

Fast Delivery

</div>


<div class="feature-line">

<i class="fa fa-refresh"></i>

Easy Order Process

</div>


<div class="feature-line">

<i class="fa fa-check-circle"></i>

Quality Product

</div>


</div>


<!-- ADD TO CART -->

<button
    type="button"
    class="detail-add-button"
    id="addToCartButton"
    data-id="<?php
        echo (int)$product['id'];
    ?>">

<i class="fa fa-shopping-bag"></i>

&nbsp; Add to Cart

</button>


<a
    href="products.php"
    class="continue-shopping">

Continue Shopping
<i class="fa fa-angle-right"></i>

</a>


</div>

</div>


</div>

</div>

</div>

</div>


<!-- =====================================================
     TOAST
===================================================== -->

<div
    class="toast-box"
    id="toastBox">

<i class="fa fa-check-circle"></i>

<span id="toastText">
Product added to cart
</span>

</div>


<script>

/* =====================================================
   ADD TO CART
===================================================== */

$("#addToCartButton").on(
    "click",
    function() {

        var button = $(this);

        var productId =
            button.data("id");


        if (
            button.data("processing") === true
        ) {

            return;

        }


        button.data(
            "processing",
            true
        );


        var oldText =
            button.html();


        button
            .prop("disabled", true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> Adding...'
            );


        $.ajax({

            url: "cart-add.php",

            type: "GET",

            data: {
                id: productId
            },

            dataType: "text",

            cache: false,


            success:
            function(response) {

                response =
                    $.trim(response);


                if (
                    response === "success"
                ) {


                    button.html(
                        '<i class="fa fa-check"></i> Added to Cart'
                    );


                    showToast(
                        "Product added to cart"
                    );


                    updateCartCount();


                    setTimeout(
                        function() {

                            button
                                .html(oldText)
                                .prop(
                                    "disabled",
                                    false
                                );

                            button.data(
                                "processing",
                                false
                            );

                        },
                        1200
                    );


                } else {


                    button
                        .html(oldText)
                        .prop(
                            "disabled",
                            false
                        );


                    button.data(
                        "processing",
                        false
                    );


                    showToast(
                        response
                    );

                }

            },


            error:
            function() {

                button
                    .html(oldText)
                    .prop(
                        "disabled",
                        false
                    );


                button.data(
                    "processing",
                    false
                );


                showToast(
                    "Product could not be added."
                );

            }

        });

    }
);


/* =====================================================
   CART COUNT
===================================================== */

function updateCartCount() {

    $.ajax({

        url: "cart-count.php",

        type: "GET",

        cache: false,

        success:
        function(response) {

            response =
                $.trim(response);


            if (
                $.isNumeric(response)
            ) {

                $("#cartCount")
                    .text(response);

            }

        }

    });

}


updateCartCount();


/* =====================================================
   TOAST
===================================================== */

function showToast(message) {

    $("#toastText")
        .text(message);


    $("#toastBox")
        .stop(true, true)
        .fadeIn(200)
        .delay(1800)
        .fadeOut(400);

}

</script>


</body>

</html>