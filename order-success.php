<?php

require("includes/common.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =========================
   LOGIN CHECK
========================= */

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];


/* =========================
   GET ORDER ID
========================= */

$order_parameter = trim(
    $_GET['order_id'] ?? ''
);

if ($order_parameter === '') {
    header("Location: products.php");
    exit();
}


/* =========================
   ESCAPE ORDER PARAMETER
========================= */

$order_parameter_db = mysqli_real_escape_string(
    $con,
    $order_parameter
);


/* =========================
   GET ORDER
========================= */

/*
   This supports both:

   1. Actual order code
      ORD-20260820-ABC123

   2. Database numeric ID
      13
*/

if (ctype_digit($order_parameter)) {

    $order_db_id = (int)$order_parameter;

    $order_query = "
        SELECT
            id,
            order_id,
            user_id,
            full_name,
            email,
            phone,
            address,
            city,
            postal_code,
            total_amount,
            payment_method,
            payment_status,
            order_status,
            card_last4,
            created_at
        FROM orders
        WHERE id = $order_db_id
        AND user_id = $user_id
        LIMIT 1
    ";

} else {

    $order_query = "
        SELECT
            id,
            order_id,
            user_id,
            full_name,
            email,
            phone,
            address,
            city,
            postal_code,
            total_amount,
            payment_method,
            payment_status,
            order_status,
            card_last4,
            created_at
        FROM orders
        WHERE order_id = '$order_parameter_db'
        AND user_id = $user_id
        LIMIT 1
    ";
}


$order_result = mysqli_query(
    $con,
    $order_query
);


/* =========================
   QUERY ERROR
========================= */

if (!$order_result) {

    die(
        "<div style='
            font-family:Arial;
            max-width:700px;
            margin:80px auto;
            padding:30px;
            background:#fff;
            border-radius:15px;
            box-shadow:0 5px 20px rgba(0,0,0,.1);
        '>
        <h2>Order Query Failed</h2>
        <p>" .
        htmlspecialchars(
            mysqli_error($con),
            ENT_QUOTES,
            'UTF-8'
        ) .
        "</p>
        </div>"
    );
}


/* =========================
   ORDER NOT FOUND
========================= */

if (mysqli_num_rows($order_result) === 0) {

    die(
        "<div style='
            font-family:Arial;
            max-width:700px;
            margin:80px auto;
            padding:30px;
            background:#fff;
            border-radius:15px;
            text-align:center;
            box-shadow:0 5px 20px rgba(0,0,0,.1);
        '>

        <h2>Order Not Found</h2>

        <p>
            This order could not be found
            for your account.
        </p>

        <a
            href='products.php'
            style='
                display:inline-block;
                margin-top:15px;
                padding:12px 25px;
                background:#0071e3;
                color:white;
                text-decoration:none;
                border-radius:25px;
            '
        >
            Continue Shopping
        </a>

        </div>"
    );
}


$order = mysqli_fetch_assoc(
    $order_result
);


/* =========================
   SAFE VALUES
========================= */

$order_code =
    $order['order_id'] ?? 'N/A';

$full_name =
    $order['full_name'] ?? '';

$email =
    $order['email'] ?? '';

$phone =
    $order['phone'] ?? '';

$address =
    $order['address'] ?? '';

$city =
    $order['city'] ?? '';

$postal_code =
    $order['postal_code'] ?? '';

$total_amount =
    (float)($order['total_amount'] ?? 0);

$payment_method =
    $order['payment_method'] ?? 'N/A';

$payment_status =
    $order['payment_status'] ?? 'Pending';

$order_status =
    $order['order_status'] ?? 'Pending';

$card_last4 =
    $order['card_last4'] ?? '';

$created_at =
    $order['created_at'] ?? '';


/* =========================
   FULL ADDRESS
========================= */

$full_address = '';

if ($address !== '') {

    $full_address .= $address;
}

if ($city !== '') {

    if ($full_address !== '') {
        $full_address .= ", ";
    }

    $full_address .= $city;
}

if ($postal_code !== '') {

    if ($full_address !== '') {
        $full_address .= " - ";
    }

    $full_address .= $postal_code;
}


/* =========================
   HTML ESCAPE FUNCTION
========================= */

function safe_value(
    $value,
    $default = 'N/A'
) {

    if (
        $value === null ||
        trim((string)$value) === ''
    ) {

        return $default;
    }

    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    E-Store | Order Confirmed
</title>


<link
    rel="stylesheet"
    href="bootstrap/css/bootstrap.min.css"
>

<link
    rel="stylesheet"
    href="css/font-awesome/css/font-awesome.min.css"
>


<style>

* {
    box-sizing: border-box;
}


body {

    margin: 0;

    min-height: 100vh;

    background: #f5f5f7;

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;

    color: #1d1d1f;
}


/* =========================
   MAIN
========================= */

.page {

    width: 100%;

    padding: 50px 20px 70px;
}


/* =========================
   CARD
========================= */

.success-card {

    width: 100%;

    max-width: 720px;

    margin: auto;

    background: white;

    border-radius: 25px;

    padding: 45px 35px;

    text-align: center;

    box-shadow:
        0 20px 60px
        rgba(0,0,0,.10);
}


/* =========================
   SUCCESS ICON
========================= */

.success-icon {

    width: 85px;

    height: 85px;

    margin: auto;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #e8f8ed;

    color: #28a745;

    font-size: 42px;
}


/* =========================
   TITLE
========================= */

h1 {

    margin: 25px 0 10px;

    font-size: 34px;

    font-weight: 700;
}


.message {

    color: #6e6e73;

    line-height: 1.6;

    font-size: 15px;
}


/* =========================
   ORDER NUMBER
========================= */

.order-number {

    display: inline-block;

    background: #f5f5f7;

    padding: 13px 20px;

    border-radius: 12px;

    margin: 18px 0;

    font-weight: 700;

    font-size: 15px;

    word-break: break-word;
}


/* =========================
   INFO
========================= */

.order-info {

    margin-top: 25px;

    background: #f8f8fa;

    border-radius: 16px;

    padding: 20px;

    text-align: left;
}


.info-title {

    margin: 0 0 15px;

    font-size: 18px;

    font-weight: 700;
}


.order-info-row {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    gap: 20px;

    padding: 10px 0;

    border-bottom:
        1px solid #e5e5e7;
}


.order-info-row:last-child {

    border-bottom: none;
}


.label {

    color: #6e6e73;

    font-size: 14px;
}


.value {

    font-weight: 600;

    text-align: right;

    font-size: 14px;

    word-break: break-word;
}


/* =========================
   DELIVERY
========================= */

.delivery {

    margin-top: 20px;

    background: #f8fbff;

    border: 1px solid #dbeeff;

    border-radius: 16px;

    padding: 20px;

    text-align: left;
}


.delivery-title {

    margin: 0 0 15px;

    font-size: 18px;

    font-weight: 700;
}


.delivery-item {

    padding: 9px 0;
}


.delivery-label {

    display: block;

    color: #6e6e73;

    font-size: 12px;

    margin-bottom: 4px;

    text-transform: uppercase;
}


.delivery-value {

    font-size: 14px;

    font-weight: 600;

    word-break: break-word;

    line-height: 1.5;
}


/* =========================
   STATUS
========================= */

.status {

    display: inline-block;

    padding: 5px 11px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: 600;
}


.status-paid {

    background: #d4edda;

    color: #155724;
}


.status-pending {

    background: #fff3cd;

    color: #856404;
}


.status-cancelled {

    background: #f8d7da;

    color: #721c24;
}


/* =========================
   BUTTON
========================= */

.shop-btn {

    display: inline-block;

    margin-top: 28px;

    background: #0071e3;

    color: white;

    padding: 13px 28px;

    border-radius: 25px;

    text-decoration: none;

    font-weight: 600;
}


.shop-btn:hover {

    background: #0077ed;

    color: white;

    text-decoration: none;
}


/* =========================
   RESPONSIVE
========================= */

@media(max-width:600px) {

    .page {

        padding:
            25px 12px 40px;
    }

    .success-card {

        padding:
            35px 18px;

        border-radius: 20px;
    }

    h1 {

        font-size: 28px;
    }

    .order-info-row {

        flex-direction: column;

        gap: 4px;
    }

    .value {

        text-align: left;
    }

}

</style>

</head>


<body>


<div class="page">


<div class="success-card">


<!-- SUCCESS ICON -->

<div class="success-icon">

    <i class="fa fa-check"></i>

</div>


<!-- TITLE -->

<h1>

    Order Confirmed!

</h1>


<p class="message">

    Thank you for shopping with E-Store.

    Your order has been successfully placed.

</p>


<!-- ORDER NUMBER -->

<div class="order-number">

    Order #

    <?php

    echo safe_value(
        $order_code
    );

    ?>

</div>


<!-- ORDER INFORMATION -->

<div class="order-info">


<h3 class="info-title">

    <i class="fa fa-file-text-o"></i>

    Order Information

</h3>


<div class="order-info-row">

    <span class="label">
        Total Amount
    </span>

    <span class="value">

        Rs.

        <?php

        echo number_format(
            $total_amount,
            2
        );

        ?>

    </span>

</div>


<div class="order-info-row">

    <span class="label">
        Payment Method
    </span>

    <span class="value">

        <?php

        echo safe_value(
            $payment_method
        );

        ?>

    </span>

</div>


<div class="order-info-row">

    <span class="label">
        Payment Status
    </span>

    <span class="value">


        <?php

        $payment_lower =
            strtolower(
                $payment_status
            );


        if (
            strpos(
                $payment_lower,
                'paid'
            ) !== false
        ) {

            $payment_class =
                'status-paid';

        } elseif (
            strpos(
                $payment_lower,
                'cancel'
            ) !== false
        ) {

            $payment_class =
                'status-cancelled';

        } else {

            $payment_class =
                'status-pending';
        }

        ?>


        <span
            class="status
            <?php
            echo $payment_class;
            ?>"
        >

            <?php

            echo safe_value(
                $payment_status,
                'Pending'
            );

            ?>

        </span>


    </span>

</div>


<div class="order-info-row">

    <span class="label">
        Order Status
    </span>

    <span class="value">

        <?php

        echo safe_value(
            $order_status,
            'Pending'
        );

        ?>

    </span>

</div>


<?php if ($created_at !== ''): ?>

<div class="order-info-row">

    <span class="label">
        Order Date
    </span>

    <span class="value">

        <?php

        echo date(
            'd M Y h:i A',
            strtotime(
                $created_at
            )
        );

        ?>

    </span>

</div>

<?php endif; ?>


</div>


<!-- DELIVERY INFORMATION -->

<div class="delivery">


<h3 class="delivery-title">

    <i class="fa fa-truck"></i>

    Delivery Information

</h3>


<div class="delivery-item">

    <span class="delivery-label">
        Full Name
    </span>

    <div class="delivery-value">

        <?php

        echo safe_value(
            $full_name
        );

        ?>

    </div>

</div>


<div class="delivery-item">

    <span class="delivery-label">
        Email
    </span>

    <div class="delivery-value">

        <?php

        echo safe_value(
            $email
        );

        ?>

    </div>

</div>


<div class="delivery-item">

    <span class="delivery-label">
        Phone
    </span>

    <div class="delivery-value">

        <?php

        echo safe_value(
            $phone
        );

        ?>

    </div>

</div>


<div class="delivery-item">

    <span class="delivery-label">
        Delivery Address
    </span>

    <div class="delivery-value">

        <?php

        if ($full_address !== '') {

            echo nl2br(
                htmlspecialchars(
                    $full_address,
                    ENT_QUOTES,
                    'UTF-8'
                )
            );

        } else {

            echo 'Address not available';
        }

        ?>

    </div>

</div>


<?php if ($card_last4 !== ''): ?>

<div class="delivery-item">

    <span class="delivery-label">
        Card
    </span>

    <div class="delivery-value">

        ****
        <?php

        echo htmlspecialchars(
            $card_last4,
            ENT_QUOTES,
            'UTF-8'
        );

        ?>

    </div>

</div>

<?php endif; ?>


</div>


<!-- MESSAGE -->

<p
    class="message"
    style="margin-top:22px;"
>

    We have received your order and
    will process it shortly.

</p>


<!-- BUTTON -->

<a
    href="products.php"
    class="shop-btn"
>

    <i class="fa fa-shopping-bag"></i>

    Continue Shopping

</a>


</div>


</div>


</body>

</html>