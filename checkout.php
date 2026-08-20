<?php

require("includes/common.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];


/* =========================
   GET CART
========================= */

$query = "
    SELECT 
        users_items.id AS cart_id,
        users_items.item_id,
        products.name,
        products.price,
        products.image
    FROM users_items
    INNER JOIN products
        ON users_items.item_id = products.id
    WHERE users_items.user_id = $user_id
    AND users_items.status = 'Added to cart'
    ORDER BY users_items.id DESC
";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Checkout query failed: " . mysqli_error($con));
}


$products = array();
$total = 0;
$total_items = 0;


/* =========================
   GROUP PRODUCTS
========================= */

while ($row = mysqli_fetch_assoc($result)) {

    $item_id = (int)$row['item_id'];

    if (!isset($products[$item_id])) {

        $products[$item_id] = array(
            "item_id" => $item_id,
            "name" => $row['name'],
            "price" => (float)$row['price'],
            "image" => $row['image'],
            "quantity" => 0
        );
    }

    $products[$item_id]['quantity']++;
}


foreach ($products as $product) {

    $total += $product['price'] * $product['quantity'];
    $total_items += $product['quantity'];
}


/* =========================
   USER DATA
========================= */

$user_query = "
    SELECT email
    FROM users
    WHERE id = $user_id
    LIMIT 1
";

$user_result = mysqli_query($con, $user_query);

$user_email = "";

if ($user_result && mysqli_num_rows($user_result) > 0) {

    $user_data = mysqli_fetch_assoc($user_result);

    $user_email = $user_data['email'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>E-Store | Checkout</title>

<link rel="stylesheet"
      href="bootstrap/css/bootstrap.min.css">

<link rel="stylesheet"
      href="css/font-awesome/css/font-awesome.min.css">


<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    padding-top: 65px;

    background: #f5f5f7;

    color: #1d1d1f;

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;
}


/* =========================
   TOP BAR
========================= */

.topbar {

    position: fixed;

    top: 0;
    left: 0;

    width: 100%;

    height: 65px;

    background: rgba(255,255,255,.95);

    backdrop-filter: blur(20px);

    border-bottom: 1px solid #e5e5e7;

    z-index: 9999;
}


.topbar-inner {

    max-width: 1150px;

    height: 65px;

    margin: auto;

    padding: 0 20px;

    display: flex;

    align-items: center;

    justify-content: space-between;
}


.logo {

    font-size: 21px;

    font-weight: 700;

    color: #111;

    text-decoration: none;
}


.logo:hover {

    color: #111;

    text-decoration: none;
}


.secure {

    color: #6e6e73;

    font-size: 13px;
}


.secure i {

    color: #28a745;
}


/* =========================
   HERO
========================= */

.hero {

    background: white;

    text-align: center;

    padding: 55px 20px 40px;

    border-bottom: 1px solid #e5e5e7;
}


.hero h1 {

    font-size: 44px;

    font-weight: 700;

    margin: 0;
}


.hero p {

    color: #6e6e73;

    margin-top: 10px;
}


/* =========================
   MAIN
========================= */

.container-main {

    max-width: 1150px;

    margin: 40px auto 80px;

    padding: 0 20px;
}


.grid {

    display: grid;

    grid-template-columns: 1fr 400px;

    gap: 25px;
}


/* =========================
   BOX
========================= */

.box {

    background: white;

    border: 1px solid #e5e5e7;

    border-radius: 20px;

    overflow: hidden;

    box-shadow:
        0 5px 20px rgba(0,0,0,.04);

    margin-bottom: 25px;
}


.box-title {

    padding: 25px;

    border-bottom: 1px solid #e5e5e7;
}


.box-title h2 {

    margin: 0;

    font-size: 23px;

    font-weight: 700;
}


.box-title p {

    color: #6e6e73;

    margin: 7px 0 0;
}


/* =========================
   CUSTOMER DETAILS
========================= */

.customer-box {

    padding: 25px;
}


.customer-box h2 {

    margin: 0 0 20px;

    font-size: 23px;
}


.form-group {

    margin-bottom: 18px;
}


.form-group label {

    display: block;

    font-size: 13px;

    font-weight: 600;

    margin-bottom: 7px;
}


.form-control-custom {

    width: 100%;

    height: 48px;

    border: 1px solid #d2d2d7;

    border-radius: 10px;

    padding: 0 14px;

    font-size: 14px;

    outline: none;

    background: #fafafa;
}


.form-control-custom:focus {

    border-color: #0071e3;

    background: white;

    box-shadow:
        0 0 0 3px
        rgba(0,113,227,.10);
}


textarea.form-control-custom {

    height: 90px;

    padding-top: 12px;

    resize: vertical;
}


.two-fields {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 15px;
}


/* =========================
   PRODUCTS
========================= */

.product {

    display: flex;

    align-items: center;

    padding: 22px;

    border-bottom: 1px solid #eee;
}


.product:last-child {

    border-bottom: none;
}


.product-img {

    width: 85px;

    height: 85px;

    border-radius: 14px;

    background: #f7f7f7;

    padding: 8px;

    flex-shrink: 0;
}


.product-img img {

    width: 100%;

    height: 100%;

    object-fit: contain;
}


.product-info {

    flex: 1;

    padding-left: 18px;
}


.product-info h3 {

    font-size: 16px;

    margin: 0 0 8px;

    font-weight: 600;
}


.product-info p {

    margin: 4px 0;

    color: #6e6e73;

    font-size: 13px;
}


.product-total {

    font-weight: 600;

    text-align: right;
}


/* =========================
   SUMMARY
========================= */

.summary {

    background: white;

    border: 1px solid #e5e5e7;

    border-radius: 20px;

    padding: 28px;

    box-shadow:
        0 5px 20px rgba(0,0,0,.04);

    position: sticky;

    top: 90px;
}


.summary h2 {

    margin-top: 0;

    font-size: 23px;
}


.summary-row {

    display: flex;

    justify-content: space-between;

    padding: 12px 0;

    color: #6e6e73;
}


.summary-total {

    border-top: 1px solid #ddd;

    margin-top: 10px;

    padding-top: 20px;

    font-size: 21px;

    font-weight: 700;

    color: #111;
}


/* =========================
   PAYMENT
========================= */

.payment-title {

    margin-top: 25px;

    font-weight: 700;

    font-size: 17px;
}


.payment-option {

    display: block;

    border: 1px solid #d2d2d7;

    border-radius: 14px;

    padding: 16px;

    margin-top: 12px;

    cursor: pointer;

    transition: .2s;
}


.payment-option:hover {

    border-color: #0071e3;

    background: #f8fbff;
}


.payment-option input {

    margin-right: 8px;
}


.payment-icon {

    font-size: 21px;

    margin-right: 8px;
}


.option-title {

    font-weight: 600;
}


.option-description {

    display: block;

    color: #6e6e73;

    font-size: 12px;

    margin-left: 27px;

    margin-top: 4px;
}


/* =========================
   CARD DETAILS
========================= */

.card-details {

    display: none;

    margin-top: 15px;

    padding: 15px;

    border-radius: 14px;

    background: #f5f5f7;
}


.card-details.show {

    display: block;
}


.card-row {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 10px;
}


/* =========================
   BUTTON
========================= */

.order-btn {

    width: 100%;

    border: none;

    border-radius: 25px;

    padding: 15px;

    margin-top: 22px;

    background: #0071e3;

    color: white;

    font-size: 16px;

    font-weight: 600;

    cursor: pointer;
}


.order-btn:hover {

    background: #0077ed;
}


.back {

    display: block;

    text-align: center;

    margin-top: 15px;

    color: #0071e3;

    text-decoration: none;
}


.security {

    background: #f5f5f7;

    border-radius: 12px;

    padding: 14px;

    text-align: center;

    margin-top: 18px;

    color: #6e6e73;

    font-size: 12px;
}


/* =========================
   EMPTY
========================= */

.empty {

    max-width: 600px;

    margin: 80px auto;

    background: white;

    padding: 60px 30px;

    text-align: center;

    border-radius: 20px;
}


.shop-btn {

    display: inline-block;

    background: #0071e3;

    color: white;

    padding: 12px 25px;

    border-radius: 25px;

    text-decoration: none;
}


.shop-btn:hover {

    color: white;

    text-decoration: none;
}


/* =========================
   MOBILE
========================= */

@media(max-width:850px) {

    .grid {

        grid-template-columns: 1fr;
    }

    .summary {

        position: static;
    }
}


@media(max-width:600px) {

    .hero h1 {

        font-size: 34px;
    }

    .product {

        padding: 15px;
    }

    .product-img {

        width: 70px;

        height: 70px;
    }

    .product-info {

        padding-left: 12px;
    }

    .product-total {

        font-size: 13px;
    }

    .two-fields {

        grid-template-columns: 1fr;
    }

    .card-row {

        grid-template-columns: 1fr;
    }
}

</style>

</head>


<body>


<!-- TOP BAR -->

<div class="topbar">

    <div class="topbar-inner">

        <a href="products.php" class="logo">
            E-Store
        </a>

        <div class="secure">

            <i class="fa fa-lock"></i>

            Secure Checkout

        </div>

    </div>

</div>


<!-- HERO -->

<div class="hero">

    <h1>Checkout</h1>

    <p>
        Enter your delivery details and complete your order.
    </p>

</div>


<?php if (!empty($products)): ?>


<div class="container-main">


<form
    action="order-confirm.php"
    method="POST"
    id="checkoutForm"
>


<div class="grid">


<!-- LEFT SIDE -->

<div>


<!-- CUSTOMER DETAILS -->

<div class="box">

<div class="customer-box">

<h2>
    <i class="fa fa-user"></i>
    Delivery Information
</h2>


<div class="form-group">

<label>
    Full Name
</label>

<input
    type="text"
    name="full_name"
    class="form-control-custom"
    placeholder="Enter your full name"
    required
>

</div>


<div class="form-group">

<label>
    Email Address
</label>

<input
    type="email"
    name="email"
    class="form-control-custom"
    value="<?php echo htmlspecialchars($user_email); ?>"
    placeholder="Enter your email"
    required
>

</div>


<div class="form-group">

<label>
    Phone Number
</label>

<input
    type="text"
    name="phone"
    class="form-control-custom"
    placeholder="03XX-XXXXXXX"
    required
>

</div>


<div class="form-group">

<label>
    Complete Address
</label>

<textarea
    name="address"
    class="form-control-custom"
    placeholder="House / Street / Area / Address"
    required
></textarea>

</div>


<div class="two-fields">


<div class="form-group">

<label>
    City
</label>

<input
    type="text"
    name="city"
    class="form-control-custom"
    placeholder="City"
    required
>

</div>


<div class="form-group">

<label>
    Postal Code
</label>

<input
    type="text"
    name="postal_code"
    class="form-control-custom"
    placeholder="Postal Code"
>

</div>


</div>

</div>

</div>


<!-- PRODUCTS -->

<div class="box">

<div class="box-title">

<h2>
    Your Order
</h2>

<p>

<?php echo $total_items; ?>

item<?php echo $total_items != 1 ? 's' : ''; ?>

in your cart

</p>

</div>


<?php foreach ($products as $product): ?>


<?php

$subtotal =
    $product['price'] *
    $product['quantity'];

?>


<div class="product">


<div class="product-img">

<img
    src="img/<?php echo htmlspecialchars($product['image']); ?>"
    alt="<?php echo htmlspecialchars($product['name']); ?>"
>

</div>


<div class="product-info">

<h3>

<?php
echo htmlspecialchars(
    $product['name']
);
?>

</h3>


<p>

Quantity:

<strong>

<?php
echo $product['quantity'];
?>

</strong>

</p>


<p>

Rs.

<?php
echo number_format(
    $product['price'],
    2
);
?>

each

</p>

</div>


<div class="product-total">

Rs.

<?php
echo number_format(
    $subtotal,
    2
);
?>

</div>


</div>


<?php endforeach; ?>


</div>

</div>


<!-- RIGHT SIDE -->

<div class="summary">


<h2>
    Order Summary
</h2>


<div class="summary-row">

<span>
    Products
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


<div class="summary-total">

<div class="summary-row">

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

</div>


<!-- PAYMENT -->

<div class="payment-title">

Choose Payment Method

</div>


<!-- COD -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="Cash on Delivery"
    checked
>

<span class="payment-icon">
    💵
</span>

<span class="option-title">
    Cash on Delivery
</span>

<span class="option-description">
    Pay when your order is delivered.
</span>

</label>


<!-- CARD -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="Card"
    id="cardPayment"
>

<span class="payment-icon">
    💳
</span>

<span class="option-title">
    Debit / Credit Card
</span>

<span class="option-description">
    Enter your card details to continue.
</span>

</label>


<!-- CARD DETAILS -->

<div
    class="card-details"
    id="cardDetails"
>


<div class="form-group">

<label>
    Cardholder Name
</label>

<input
    type="text"
    name="card_name"
    class="form-control-custom"
    placeholder="Name on card"
>

</div>


<div class="form-group">

<label>
    Card Number
</label>

<input
    type="text"
    name="card_number"
    id="cardNumber"
    class="form-control-custom"
    placeholder="XXXX XXXX XXXX XXXX"
    maxlength="19"
>

</div>


<div class="card-row">


<div class="form-group">

<label>
    Expiry
</label>

<input
    type="text"
    name="card_expiry"
    class="form-control-custom"
    placeholder="MM/YY"
    maxlength="5"
>

</div>


<div class="form-group">

<label>
    CVV
</label>

<input
    type="password"
    name="card_cvv"
    class="form-control-custom"
    placeholder="CVV"
    maxlength="4"
>

</div>


</div>


<div
    style="
    font-size:11px;
    color:#777;
    margin-top:5px;
    "
>

<i class="fa fa-lock"></i>

Card details are used only for this checkout.

</div>


</div>


<!-- SUBMIT -->

<button
    type="submit"
    class="order-btn"
>

<i class="fa fa-check"></i>

Confirm Order

</button>


<a
    href="cart.php"
    class="back"
>

<i class="fa fa-arrow-left"></i>

Back to Cart

</a>


<div class="security">

<i class="fa fa-shield"></i>

Your order information is protected.

</div>


</div>


</div>


</form>


</div>


<?php else: ?>


<div class="empty">

<h2>
    Your cart is empty
</h2>

<p>
    Add some products before checkout.
</p>

<a
    href="products.php"
    class="shop-btn"
>
    Continue Shopping
</a>

</div>


<?php endif; ?>


<script src="bootstrap/js/jquery-3.5.1.min.js"></script>


<script>

/* =========================
   SHOW CARD DETAILS
========================= */

$("input[name='payment_method']").on(
    "change",
    function() {

        if ($(this).val() === "Card") {

            $("#cardDetails")
                .addClass("show");

            $("#cardNumber")
                .attr("required", true);

            $("input[name='card_name']")
                .attr("required", true);

            $("input[name='card_expiry']")
                .attr("required", true);

            $("input[name='card_cvv']")
                .attr("required", true);

        } else {

            $("#cardDetails")
                .removeClass("show");

            $("#cardNumber")
                .removeAttr("required");

            $("input[name='card_name']")
                .removeAttr("required");

            $("input[name='card_expiry']")
                .removeAttr("required");

            $("input[name='card_cvv']")
                .removeAttr("required");
        }

    }
);


/* =========================
   CARD NUMBER FORMAT
========================= */

$("#cardNumber").on(
    "input",
    function() {

        var value =
            $(this)
            .val()
            .replace(/\D/g, "")
            .substring(0,16);

        var formatted =
            value.match(/.{1,4}/g);

        $(this).val(
            formatted
                ? formatted.join(" ")
                : ""
        );

    }
);


/* =========================
   EXPIRY FORMAT
========================= */

$("input[name='card_expiry']").on(
    "input",
    function() {

        var value =
            $(this)
            .val()
            .replace(/\D/g, "")
            .substring(0,4);

        if (value.length >= 3) {

            value =
                value.substring(0,2)
                + "/"
                + value.substring(2);
        }

        $(this).val(value);

    }
);

</script>


</body>
</html>