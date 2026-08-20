<?php

require("includes/common.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =====================================================
   LOGIN CHECK
===================================================== */

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['email'])
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
   PAYMENT DATA
===================================================== */

$posted_total = isset($_POST['total'])
    ? (float) $_POST['total']
    : 0;

$card_holder = isset($_POST['card_holder'])
    ? trim($_POST['card_holder'])
    : '';

$card_number = isset($_POST['card_number'])
    ? preg_replace('/\D/', '', $_POST['card_number'])
    : '';

$expiry = isset($_POST['expiry'])
    ? trim($_POST['expiry'])
    : '';

$cvv = isset($_POST['cvv'])
    ? preg_replace('/\D/', '', $_POST['cvv'])
    : '';


/* =====================================================
   BASIC VALIDATION
===================================================== */

if ($posted_total <= 0) {
    die("Invalid order amount.");
}

if ($card_holder === '') {
    die("Card holder name is required.");
}

if (strlen($card_number) < 12) {
    die("Invalid card number.");
}

if (strlen($cvv) < 3) {
    die("Invalid CVV.");
}

if (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) {
    die("Invalid expiry date.");
}


/*
 * IMPORTANT:
 * Card number / CVV database mein save nahi kar rahe.
 */


/* =====================================================
   GET CART
===================================================== */

$cart_sql = "
    SELECT
        users_items.id AS cart_id,
        users_items.item_id AS item_id,
        users_items.user_id AS user_id,
        products.name AS product_name,
        products.price AS product_price,
        products.image AS product_image

    FROM users_items

    INNER JOIN products
        ON users_items.item_id = products.id

    WHERE users_items.user_id = ?
    AND users_items.status = 'Added to cart'

    ORDER BY users_items.id ASC
";


$cart_stmt = mysqli_prepare(
    $con,
    $cart_sql
);


if (!$cart_stmt) {
    die(
        "Cart prepare failed: " .
        mysqli_error($con)
    );
}


mysqli_stmt_bind_param(
    $cart_stmt,
    "i",
    $user_id
);


if (!mysqli_stmt_execute($cart_stmt)) {
    die(
        "Cart query failed: " .
        mysqli_stmt_error($cart_stmt)
    );
}


$cart_result =
    mysqli_stmt_get_result($cart_stmt);


if (!$cart_result) {
    die(
        "Cart result failed: " .
        mysqli_error($con)
    );
}


if (mysqli_num_rows($cart_result) == 0) {

    header("Location: cart.php");

    exit();
}


/* =====================================================
   SAVE CART ITEMS
===================================================== */

$cart_items = array();

$total = 0;


while ($row = mysqli_fetch_assoc($cart_result)) {

    $price =
        (float) $row['product_price'];

    $total += $price;

    $cart_items[] = $row;
}


mysqli_stmt_close($cart_stmt);


/* =====================================================
   UNIQUE ORDER ID
===================================================== */

function createUniqueOrderId($con)
{

    do {

        $order_id =
            "ORD-" .
            date("YmdHis") .
            "-" .
            strtoupper(
                substr(
                    bin2hex(
                        random_bytes(4)
                    ),
                    0,
                    8
                )
            );


        $check_sql = "
            SELECT id
            FROM orders
            WHERE order_id = ?
            LIMIT 1
        ";


        $check_stmt =
            mysqli_prepare(
                $con,
                $check_sql
            );


        if (!$check_stmt) {

            die(
                "Order ID check failed: " .
                mysqli_error($con)
            );
        }


        mysqli_stmt_bind_param(
            $check_stmt,
            "s",
            $order_id
        );


        mysqli_stmt_execute(
            $check_stmt
        );


        mysqli_stmt_store_result(
            $check_stmt
        );


        $exists =
            mysqli_stmt_num_rows(
                $check_stmt
            ) > 0;


        mysqli_stmt_close(
            $check_stmt
        );


    } while ($exists);


    return $order_id;
}


$order_id =
    createUniqueOrderId($con);


/* =====================================================
   DATABASE TRANSACTION
===================================================== */

mysqli_begin_transaction($con);


try {


    /* =================================================
       INSERT ORDER
    ================================================= */

    $order_sql = "
        INSERT INTO orders
        (
            order_id,
            user_id,
            total_amount,
            payment_status,
            order_status,
            payment_method
        )
        VALUES
        (
            ?,
            ?,
            ?,
            'Paid',
            'Confirmed',
            'Card'
        )
    ";


    $order_stmt =
        mysqli_prepare(
            $con,
            $order_sql
        );


    if (!$order_stmt) {

        throw new Exception(
            "Order prepare failed: " .
            mysqli_error($con)
        );
    }


    mysqli_stmt_bind_param(
        $order_stmt,
        "sid",
        $order_id,
        $user_id,
        $total
    );


    if (
        !mysqli_stmt_execute(
            $order_stmt
        )
    ) {

        throw new Exception(
            "Order insert failed: " .
            mysqli_stmt_error(
                $order_stmt
            )
        );
    }


    /*
     * orders.id
     */
    $db_order_id =
        mysqli_insert_id($con);


    mysqli_stmt_close(
        $order_stmt
    );


    /* =================================================
       INSERT ORDER ITEMS
    ================================================= */

    $item_sql = "
        INSERT INTO order_items
        (
            order_id,
            user_id,
            item_id,
            quantity,
            price
        )
        VALUES
        (
            ?,
            ?,
            ?,
            1,
            ?
        )
    ";


    $item_stmt =
        mysqli_prepare(
            $con,
            $item_sql
        );


    if (!$item_stmt) {

        throw new Exception(
            "Order item prepare failed: " .
            mysqli_error($con)
        );
    }


    foreach ($cart_items as $item) {

        $item_id =
            (int) $item['item_id'];

        $item_price =
            (float) $item['product_price'];


        mysqli_stmt_bind_param(
            $item_stmt,
            "iiid",
            $db_order_id,
            $user_id,
            $item_id,
            $item_price
        );


        if (
            !mysqli_stmt_execute(
                $item_stmt
            )
        ) {

            throw new Exception(
                "Order item insert failed: " .
                mysqli_stmt_error(
                    $item_stmt
                )
            );
        }
    }


    mysqli_stmt_close(
        $item_stmt
    );


    /* =================================================
       UPDATE CART
    ================================================= */

    $update_sql = "
        UPDATE users_items
        SET status = 'Confirmed'
        WHERE user_id = ?
        AND status = 'Added to cart'
    ";


    $update_stmt =
        mysqli_prepare(
            $con,
            $update_sql
        );


    if (!$update_stmt) {

        throw new Exception(
            "Cart update prepare failed: " .
            mysqli_error($con)
        );
    }


    mysqli_stmt_bind_param(
        $update_stmt,
        "i",
        $user_id
    );


    if (
        !mysqli_stmt_execute(
            $update_stmt
        )
    ) {

        throw new Exception(
            "Cart update failed: " .
            mysqli_stmt_error(
                $update_stmt
            )
        );
    }


    mysqli_stmt_close(
        $update_stmt
    );


    /* =================================================
       COMMIT
    ================================================= */

    mysqli_commit($con);


/* =====================================================
   SUCCESS PAGE
===================================================== */

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
Order Confirmed | E-Store
</title>


<style>

* {
    box-sizing: border-box;
}


body {

    margin: 0;

    background: #f5f5f7;

    color: #1d1d1f;

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;
}


.success-wrapper {

    min-height: 100vh;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 20px;
}


.success-card {

    width: 100%;

    max-width: 570px;

    background: #fff;

    border-radius: 26px;

    padding: 50px 35px;

    text-align: center;

    box-shadow:
        0 15px 50px
        rgba(0,0,0,.08);
}


.success-icon {

    width: 85px;

    height: 85px;

    margin: 0 auto 25px;

    border-radius: 50%;

    background: #34c759;

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 43px;

    font-weight: bold;
}


h1 {

    margin: 0;

    font-size: 34px;

    letter-spacing: -1px;
}


.message {

    color: #6e6e73;

    line-height: 1.6;

    margin:
        12px 0 30px;
}


.order-box {

    background: #f5f5f7;

    border-radius: 16px;

    padding: 20px;

    margin-bottom: 25px;
}


.order-row {

    display: flex;

    justify-content: space-between;

    gap: 20px;

    padding: 9px 0;

    font-size: 14px;
}


.order-row span:first-child {

    color: #6e6e73;
}


.order-number {

    color: #0071e3;

    font-weight: 700;
}


.total {

    font-size: 18px;

    font-weight: 700;
}


.button {

    display: inline-block;

    background: #0071e3;

    color: #fff;

    text-decoration: none;

    padding:
        13px 28px;

    border-radius: 25px;

    font-weight: 600;

    transition: .2s;
}


.button:hover {

    background: #0077ed;

    color: white;

    text-decoration: none;

}


@media(max-width:600px) {

    .success-card {

        padding:
            40px 20px;
    }

    h1 {

        font-size: 28px;
    }

    .order-row {

        font-size: 13px;
    }

}

</style>

</head>


<body>


<div class="success-wrapper">

<div class="success-card">


<div class="success-icon">
✓
</div>


<h1>
Order Confirmed!
</h1>


<p class="message">

Thank you for shopping with E-Store.

Your order has been successfully placed.

</p>


<div class="order-box">


<div class="order-row">

<span>
Order ID
</span>

<span class="order-number">

<?php

echo htmlspecialchars(
    $order_id
);

?>

</span>

</div>


<div class="order-row">

<span>
Items
</span>

<span>

<?php

echo count(
    $cart_items
);

?>

</span>

</div>


<div class="order-row">

<span>
Payment
</span>

<span>
Paid
</span>

</div>


<div class="order-row">

<span>
Order Status
</span>

<span>
Confirmed
</span>

</div>


<div class="order-row">

<span>
Total
</span>

<span class="total">

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


<a
    href="products.php"
    class="button"
>

Continue Shopping

</a>


</div>

</div>


</body>

</html>

<?php


} catch (Exception $e) {


    /* =================================================
       ROLLBACK
    ================================================= */

    mysqli_rollback($con);


    die(
        "Order failed: " .
        htmlspecialchars(
            $e->getMessage()
        )
    );
}

?>