<?php

require("includes/common.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =====================================================
   LOGIN CHECK
===================================================== */

if (
    !isset($_SESSION['email']) ||
    !isset($_SESSION['user_id'])
) {
    header("Location: login.php");
    exit();
}


$user_id = (int) $_SESSION['user_id'];


/* =====================================================
   ONLY POST REQUEST
===================================================== */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit();
}


/* =====================================================
   TOTAL
===================================================== */

$total = isset($_POST['total'])
    ? (float) $_POST['total']
    : 0;


if ($total <= 0) {
    header("Location: cart.php");
    exit();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>E-Store | Secure Payment</title>


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

.payment-nav {

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

    max-width: 1050px;

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

    letter-spacing: -.5px;

    text-decoration: none;
}


.brand:hover {

    color: #111;

    text-decoration: none;
}


.secure-top {

    color: #6e6e73;

    font-size: 12px;
}


.secure-top span {

    color: #34c759;

    font-size: 15px;

    margin-right: 5px;
}


/* =====================================================
   HEADER
===================================================== */

.payment-header {

    background: #fff;

    text-align: center;

    padding:
        50px 20px 35px;

    border-bottom:
        1px solid #e5e5e7;
}


.payment-header h1 {

    margin: 0;

    font-size: 44px;

    font-weight: 700;

    letter-spacing: -2px;
}


.payment-header p {

    margin:
        10px 0 0;

    color: #6e6e73;

    font-size: 16px;
}


/* =====================================================
   PAYMENT WRAPPER
===================================================== */

.payment-wrapper {

    width: 100%;

    max-width: 520px;

    margin:
        40px auto 80px;

    padding:
        0 15px;
}


/* =====================================================
   CARD
===================================================== */

.payment-card {

    background: #fff;

    border:
        1px solid #e5e5e7;

    border-radius: 24px;

    padding: 32px;

    box-shadow:
        0 10px 35px
        rgba(0,0,0,.06);
}


/* =====================================================
   STEPS
===================================================== */

.steps {

    display: flex;

    justify-content: center;

    align-items: center;

    margin-bottom: 30px;
}


.step {

    display: flex;

    align-items: center;

    color: #6e6e73;

    font-size: 12px;

    white-space: nowrap;
}


.step.active {

    color: #0071e3;

    font-weight: 600;
}


.step-number {

    width: 25px;

    height: 25px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #e5e5e7;

    margin-right: 7px;
}


.step.active .step-number {

    background: #0071e3;

    color: #fff;
}


.step-line {

    width: 35px;

    height: 1px;

    background: #d2d2d7;

    margin:
        0 10px;
}


/* =====================================================
   PAYMENT TITLE
===================================================== */

.payment-title {

    text-align: center;

    margin-bottom: 25px;
}


.payment-title h2 {

    margin: 0;

    font-size: 24px;

    font-weight: 700;
}


.payment-title p {

    color: #6e6e73;

    font-size: 13px;

    margin:
        7px 0 0;
}


/* =====================================================
   AMOUNT
===================================================== */

.amount-box {

    background: #f5f5f7;

    border-radius: 16px;

    padding:
        20px;

    text-align: center;

    margin-bottom: 25px;
}


.amount-label {

    color: #6e6e73;

    font-size: 12px;

    margin-bottom: 5px;
}


.amount {

    font-size: 29px;

    font-weight: 700;

    letter-spacing: -.5px;
}


/* =====================================================
   FORM
===================================================== */

.form-group {

    margin-bottom: 18px;
}


.form-group label {

    display: block;

    margin-bottom: 7px;

    font-size: 13px;

    font-weight: 600;
}


.form-control {

    width: 100%;

    height: 48px;

    padding:
        0 14px;

    border:
        1px solid #d2d2d7;

    border-radius: 11px;

    background: #fff;

    color: #1d1d1f;

    font-size: 15px;

    outline: none;

    transition:
        border .2s,
        box-shadow .2s;
}


.form-control:focus {

    border-color: #0071e3;

    box-shadow:
        0 0 0 3px
        rgba(0,113,227,.12);
}


.form-control::placeholder {

    color: #999;
}


/* =====================================================
   CARD NUMBER
===================================================== */

.card-number-wrapper {

    position: relative;
}


.card-icon {

    position: absolute;

    right: 15px;

    top: 50%;

    transform: translateY(-50%);

    color: #6e6e73;

    font-size: 18px;
}


/* =====================================================
   FORM ROW
===================================================== */

.form-row {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 15px;
}


/* =====================================================
   CARD TYPE
===================================================== */

.card-types {

    display: flex;

    gap: 7px;

    margin-top: 9px;
}


.card-type {

    padding:
        4px 8px;

    border:
        1px solid #e5e5e7;

    border-radius: 5px;

    color: #777;

    font-size: 9px;

    font-weight: 700;

    background: #fafafa;
}


/* =====================================================
   PAY BUTTON
===================================================== */

.pay-button {

    width: 100%;

    height: 52px;

    margin-top: 8px;

    border: none;

    border-radius: 27px;

    background: #0071e3;

    color: #fff;

    font-size: 15px;

    font-weight: 600;

    cursor: pointer;

    transition:
        .2s;
}


.pay-button:hover {

    background: #0077ed;

    transform:
        translateY(-1px);
}


.pay-button:active {

    transform:
        translateY(0);
}


/* =====================================================
   BACK
===================================================== */

.back-link {

    display: block;

    text-align: center;

    margin-top: 18px;

    color: #0071e3;

    font-size: 14px;

    text-decoration: none;
}


.back-link:hover {

    color: #0077ed;

    text-decoration: none;
}


/* =====================================================
   SECURITY
===================================================== */

.security {

    margin-top: 25px;

    padding:
        17px;

    background: #f5f5f7;

    border-radius: 13px;

    text-align: center;

    color: #6e6e73;

    font-size: 11px;

    line-height: 1.6;
}


.security-icon {

    color: #34c759;

    font-size: 17px;

    margin-right: 5px;
}


/* =====================================================
   FOOTER
===================================================== */

.payment-footer {

    background: #fff;

    border-top:
        1px solid #e5e5e7;

    padding:
        30px 20px;

    text-align: center;

    color: #6e6e73;

    font-size: 11px;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px) {

    .payment-header {

        padding:
            40px 15px 30px;
    }


    .payment-header h1 {

        font-size: 36px;

        letter-spacing: -1px;
    }


    .payment-wrapper {

        margin-top: 25px;

        padding:
            0 12px;
    }


    .payment-card {

        padding: 22px;

        border-radius: 20px;
    }


    .steps {

        overflow-x: auto;

        justify-content: flex-start;

        padding-bottom: 5px;
    }


    .step-line {

        width: 25px;

        margin:
            0 6px;
    }


    .form-row {

        grid-template-columns: 1fr 1fr;

        gap: 10px;
    }


    .amount {

        font-size: 25px;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="payment-nav">

<div class="nav-inner">


<a
    href="products.php"
    class="brand">

E-Store

</a>


<div class="secure-top">

<span>🔒</span>

Secure Payment

</div>


</div>

</nav>


<!-- =====================================================
     HEADER
===================================================== -->

<section class="payment-header">

<h1>
Payment
</h1>

<p>
Complete your order securely.
</p>

</section>


<!-- =====================================================
     PAYMENT
===================================================== -->

<div class="payment-wrapper">


<div class="payment-card">


<!-- STEPS -->

<div class="steps">


<div class="step">

<div class="step-number">
✓
</div>

Order

</div>


<div class="step-line"></div>


<div class="step active">

<div class="step-number">
2
</div>

Payment

</div>


<div class="step-line"></div>


<div class="step">

<div class="step-number">
3
</div>

Complete

</div>


</div>


<!-- TITLE -->

<div class="payment-title">

<h2>
Card Payment
</h2>

<p>
Enter your payment details below.
</p>

</div>


<!-- AMOUNT -->

<div class="amount-box">

<div class="amount-label">
Amount to Pay
</div>

<div class="amount">

Rs.

<?php

echo number_format(
    $total,
    2
);

?>

</div>

</div>


<!-- FORM -->

<form
    action="process-payment.php"
    method="POST"
    id="paymentForm">


<input
    type="hidden"
    name="total"
    value="<?php
        echo htmlspecialchars(
            $total
        );
    ?>">


<!-- CARD HOLDER -->

<div class="form-group">

<label for="card_holder">
Card Holder Name
</label>

<input
    type="text"
    id="card_holder"
    name="card_holder"
    class="form-control"
    placeholder="Enter card holder name"
    autocomplete="cc-name"
    required>

</div>


<!-- CARD NUMBER -->

<div class="form-group">

<label for="card_number">
Card Number
</label>


<div class="card-number-wrapper">

<input
    type="text"
    id="card_number"
    name="card_number"
    class="form-control"
    placeholder="1234 5678 9012 3456"
    maxlength="19"
    inputmode="numeric"
    autocomplete="cc-number"
    required>


<div class="card-icon">
💳
</div>

</div>


<div class="card-types">

<span class="card-type">
VISA
</span>

<span class="card-type">
Mastercard
</span>

<span class="card-type">
AMEX
</span>

</div>

</div>


<!-- EXPIRY + CVV -->

<div class="form-row">


<div class="form-group">

<label for="expiry">
Expiry Date
</label>

<input
    type="text"
    id="expiry"
    name="expiry"
    class="form-control"
    placeholder="MM/YY"
    maxlength="5"
    inputmode="numeric"
    autocomplete="cc-exp"
    required>

</div>


<div class="form-group">

<label for="cvv">
CVV
</label>

<input
    type="password"
    id="cvv"
    name="cvv"
    class="form-control"
    placeholder="123"
    maxlength="4"
    inputmode="numeric"
    autocomplete="cc-csc"
    required>

</div>


</div>


<!-- PAY -->

<button
    type="submit"
    class="pay-button"
    id="payButton">

🔒

&nbsp;

Pay & Confirm Order

</button>


</form>


<!-- BACK -->

<a
    href="checkout.php"
    class="back-link">

← Back to Checkout

</a>


<!-- SECURITY -->

<div class="security">

<span class="security-icon">
🔒
</span>

<strong>
Secure Payment
</strong>

<br>

This is a demo payment form for your E-Store project.
Do not enter real card information on a local/demo website.

</div>


</div>

</div>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="payment-footer">

© <?php echo date('Y'); ?>

E-Store. All rights reserved.

&nbsp; • &nbsp;

Secure Checkout

</footer>


<script>

/* =====================================================
   CARD NUMBER FORMAT
===================================================== */

document
.getElementById("card_number")
.addEventListener("input", function () {

    let value =
        this.value.replace(/\D/g, "");

    value =
        value.substring(0, 16);

    let formatted =
        value.match(/.{1,4}/g);

    this.value =
        formatted
        ? formatted.join(" ")
        : "";

});


/* =====================================================
   EXPIRY FORMAT
===================================================== */

document
.getElementById("expiry")
.addEventListener("input", function () {

    let value =
        this.value.replace(/\D/g, "");

    value =
        value.substring(0, 4);

    if (value.length >= 3) {

        value =
            value.substring(0, 2)
            + "/"
            + value.substring(2);

    }

    this.value = value;

});


/* =====================================================
   CVV ONLY NUMBERS
===================================================== */

document
.getElementById("cvv")
.addEventListener("input", function () {

    this.value =
        this.value
        .replace(/\D/g, "")
        .substring(0, 4);

});


/* =====================================================
   CARD HOLDER
===================================================== */

document
.getElementById("card_holder")
.addEventListener("input", function () {

    this.value =
        this.value
        .replace(/[0-9]/g, "");

});


/* =====================================================
   PAYMENT BUTTON
===================================================== */

document
.getElementById("paymentForm")
.addEventListener("submit", function () {

    const button =
        document.getElementById("payButton");

    button.disabled = true;

    button.innerHTML =
        "Processing Order...";

});

</script>


</body>

</html>