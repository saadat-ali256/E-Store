<?php

// ======================================================
// ADMIN LOGIN PROTECTION
// ======================================================

require_once "admin-auth.php";

// Database connection
require_once "../includes/common.php";


// ======================================================
// ORDER ID CHECK
// ======================================================

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$id = (int) $_GET['id'];

if ($id <= 0) {
    header("Location: orders.php");
    exit();
}


// ======================================================
// GET ORDER
// ======================================================

$order_query = "
    SELECT *
    FROM orders
    WHERE id = $id
    LIMIT 1
";

$order_result = mysqli_query($con, $order_query);

if (!$order_result) {
    die(
        "Order query failed: " .
        htmlspecialchars(mysqli_error($con))
    );
}

if (mysqli_num_rows($order_result) === 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($order_result);


// ======================================================
// GET ORDER ITEMS
// ======================================================

$items_query = "
    SELECT
        id,
        order_id,
        product_id,
        product_name,
        price,
        quantity,
        subtotal,
        created_at
    FROM order_items
    WHERE order_id = $id
    ORDER BY id ASC
";

$items_result = mysqli_query($con, $items_query);

if (!$items_result) {
    die(
        "Order items query failed: " .
        htmlspecialchars(mysqli_error($con))
    );
}


// ======================================================
// SAFE DISPLAY FUNCTION
// ======================================================

function show_value($value, $default = "N/A")
{
    if ($value === null || trim((string)$value) === '') {
        return $default;
    }

    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


// ======================================================
// VALUES
// ======================================================

$order_code = $order['order_id'] ?? '';

$full_name = $order['full_name'] ?? '';
$email = $order['email'] ?? '';
$phone = $order['phone'] ?? '';
$address = $order['address'] ?? '';
$city = $order['city'] ?? '';
$postal_code = $order['postal_code'] ?? '';

$payment_method = $order['payment_method'] ?? '';

$payment_status = $order['payment_status'] ?? 'Pending';

$order_status = $order['order_status'] ?? 'Pending';

$total_amount = (float)($order['total_amount'] ?? 0);

$created_at = $order['created_at'] ?? '';

$card_last4 = $order['card_last4'] ?? '';


// ======================================================
// FULL DELIVERY ADDRESS
// ======================================================

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Order Details | E-Store Admin
    </title>

    <link rel="stylesheet"
          href="../bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="../css/font-awesome/css/font-awesome.min.css">


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


        /* =========================
           NAVBAR
        ========================= */

        .navbar-custom {
            background: #111;
            min-height: 65px;
            padding: 0 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            color: white;
            font-size: 21px;
            font-weight: 700;
            text-decoration: none;
        }

        .logo:hover {
            color: white;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links a {
            color: #ddd;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover {
            color: white;
        }

        .logout {
            color: #ff6b6b !important;
        }


        /* =========================
           MAIN
        ========================= */

        .container-main {
            max-width: 1100px;
            margin: 35px auto 80px;
            padding: 0 20px;
        }


        /* =========================
           HEADER
        ========================= */

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }

        .page-header p {
            margin: 7px 0 0;
            color: #6e6e73;
        }


        /* =========================
           BOX
        ========================= */

        .box {
            background: white;
            border: 1px solid #e5e5e7;
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 22px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
        }

        .box h2 {
            margin: 0 0 20px;
            font-size: 22px;
            font-weight: 700;
        }


        /* =========================
           INFO GRID
        ========================= */

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            background: #f5f5f7;
            border-radius: 12px;
            padding: 16px;
        }

        .info-label {
            display: block;
            color: #6e6e73;
            font-size: 12px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            word-break: break-word;
        }


        /* =========================
           DELIVERY BOX
        ========================= */

        .delivery-box {
            background: #f8fbff;
            border: 1px solid #dbeeff;
            border-radius: 15px;
            padding: 20px;
        }

        .delivery-item {
            margin-bottom: 15px;
        }

        .delivery-item:last-child {
            margin-bottom: 0;
        }

        .delivery-label {
            display: block;
            color: #6e6e73;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .delivery-value {
            font-size: 15px;
            font-weight: 600;
            word-break: break-word;
        }

        .address-value {
            line-height: 1.6;
        }


        /* =========================
           STATUS
        ========================= */

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }


        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: #f5f5f7;
            border-top: none;
            font-size: 13px;
        }

        .table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .product-name {
            font-weight: 600;
        }

        .total-row {
            font-size: 17px;
            font-weight: 700;
        }


        /* =========================
           BUTTON
        ========================= */

        .back-btn {
            display: inline-block;
            margin-bottom: 22px;
            color: #0071e3;
            text-decoration: none;
            font-weight: 500;
        }

        .back-btn:hover {
            color: #0077ed;
            text-decoration: none;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media(max-width:700px) {

            .navbar-custom {
                padding: 0 15px;
            }

            .nav-links a:not(.logout) {
                display: none;
            }

            .container-main {
                margin-top: 25px;
                padding: 0 12px;
            }

            .page-header h1 {
                font-size: 27px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .box {
                padding: 18px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<div class="navbar-custom">

    <a href="dashboard.php" class="logo">
        E-Store Admin
    </a>

    <div class="nav-links">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="orders.php">
            Orders
        </a>

        <a href="logout.php" class="logout">
            <i class="fa fa-sign-out"></i>
            Logout
        </a>

    </div>

</div>


<div class="container-main">


<!-- BACK -->

<a href="orders.php" class="back-btn">

    <i class="fa fa-arrow-left"></i>

    Back to Orders

</a>


<!-- HEADER -->

<div class="page-header">

    <h1>
        Order Details
    </h1>

    <p>
        Complete information about this customer order.
    </p>

</div>


<!-- ORDER INFORMATION -->

<div class="box">

    <h2>
        <i class="fa fa-file-text-o"></i>
        Order Information
    </h2>


    <div class="info-grid">


        <div class="info-item">

            <span class="info-label">
                Order ID
            </span>

            <div class="info-value">

                <?php
                echo show_value($order_code);
                ?>

            </div>

        </div>


        <div class="info-item">

            <span class="info-label">
                User ID
            </span>

            <div class="info-value">

                <?php
                echo (int)$order['user_id'];
                ?>

            </div>

        </div>


        <div class="info-item">

            <span class="info-label">
                Total Amount
            </span>

            <div class="info-value">

                Rs.

                <?php

                echo number_format(
                    $total_amount,
                    2
                );

                ?>

            </div>

        </div>


        <div class="info-item">

            <span class="info-label">
                Payment Method
            </span>

            <div class="info-value">

                <?php
                echo show_value($payment_method);
                ?>

            </div>

        </div>


        <div class="info-item">

            <span class="info-label">
                Payment Status
            </span>

            <div class="info-value">

                <?php

                $payment_class =
                    strtolower($payment_status);

                if (
                    strpos(
                        $payment_class,
                        'paid'
                    ) !== false
                ) {

                    $payment_status_class =
                        'status-success';

                } elseif (
                    strpos(
                        $payment_class,
                        'cancel'
                    ) !== false
                ) {

                    $payment_status_class =
                        'status-cancelled';

                } else {

                    $payment_status_class =
                        'status-pending';
                }

                ?>

                <span class="status <?php
                    echo $payment_status_class;
                ?>">

                    <?php

                    echo show_value(
                        $payment_status,
                        'Pending'
                    );

                    ?>

                </span>

            </div>

        </div>


        <div class="info-item">

            <span class="info-label">
                Order Status
            </span>

            <div class="info-value">

                <?php

                $order_class =
                    strtolower($order_status);

                if (
                    strpos(
                        $order_class,
                        'deliver'
                    ) !== false ||
                    strpos(
                        $order_class,
                        'complete'
                    ) !== false
                ) {

                    $order_status_class =
                        'status-success';

                } elseif (
                    strpos(
                        $order_class,
                        'cancel'
                    ) !== false
                ) {

                    $order_status_class =
                        'status-cancelled';

                } else {

                    $order_status_class =
                        'status-pending';
                }

                ?>

                <span class="status <?php
                    echo $order_status_class;
                ?>">

                    <?php

                    echo show_value(
                        $order_status,
                        'Pending'
                    );

                    ?>

                </span>

            </div>

        </div>


        <div class="info-item">

            <span class="info-label">
                Order Date
            </span>

            <div class="info-value">

                <?php

                if ($created_at !== '') {

                    echo date(
                        'd M Y h:i A',
                        strtotime($created_at)
                    );

                } else {

                    echo 'N/A';
                }

                ?>

            </div>

        </div>


    </div>

</div>


<!-- CUSTOMER / DELIVERY -->

<div class="box">

    <h2>

        <i class="fa fa-user"></i>

        Customer & Delivery Information

    </h2>


    <div class="delivery-box">


        <div class="delivery-item">

            <span class="delivery-label">
                Customer Name
            </span>

            <div class="delivery-value">

                <?php
                echo show_value($full_name);
                ?>

            </div>

        </div>


        <div class="delivery-item">

            <span class="delivery-label">
                Email
            </span>

            <div class="delivery-value">

                <?php
                echo show_value($email);
                ?>

            </div>

        </div>


        <div class="delivery-item">

            <span class="delivery-label">
                Phone
            </span>

            <div class="delivery-value">

                <?php
                echo show_value($phone);
                ?>

            </div>

        </div>


        <div class="delivery-item">

            <span class="delivery-label">
                Address
            </span>

            <div class="delivery-value address-value">

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

                ****<?php

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

</div>


<!-- ORDER PRODUCTS -->

<div class="box">

    <h2>

        <i class="fa fa-shopping-cart"></i>

        Ordered Products

    </h2>


    <div class="table-wrapper">

        <table class="table table-hover">

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Product
                    </th>

                    <th>
                        Price
                    </th>

                    <th>
                        Quantity
                    </th>

                    <th>
                        Subtotal
                    </th>

                </tr>

            </thead>


            <tbody>


            <?php

            $item_number = 1;
            $items_total = 0;

            ?>


            <?php if (mysqli_num_rows($items_result) > 0): ?>


                <?php while (
                    $item = mysqli_fetch_assoc($items_result)
                ): ?>


                    <?php

                    $items_total +=
                        (float)$item['subtotal'];

                    ?>


                    <tr>

                        <td>

                            <?php
                            echo $item_number++;
                            ?>

                        </td>


                        <td class="product-name">

                            <?php

                            echo show_value(
                                $item['product_name']
                            );

                            ?>

                        </td>


                        <td>

                            Rs.

                            <?php

                            echo number_format(
                                (float)$item['price'],
                                2
                            );

                            ?>

                        </td>


                        <td>

                            <?php
                            echo (int)$item['quantity'];
                            ?>

                        </td>


                        <td>

                            Rs.

                            <?php

                            echo number_format(
                                (float)$item['subtotal'],
                                2
                            );

                            ?>

                        </td>

                    </tr>


                <?php endwhile; ?>


                <tr>

                    <td
                        colspan="4"
                        class="text-right total-row"
                    >

                        Total

                    </td>

                    <td class="total-row">

                        Rs.

                        <?php

                        echo number_format(
                            $items_total,
                            2
                        );

                        ?>

                    </td>

                </tr>


            <?php else: ?>


                <tr>

                    <td
                        colspan="5"
                        class="text-center"
                    >

                        No products found for this order.

                    </td>

                </tr>


            <?php endif; ?>


            </tbody>

        </table>

    </div>

</div>


</div>

</body>

</html>