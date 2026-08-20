<?php

// ======================================================
// ADMIN LOGIN PROTECTION
// ======================================================

require_once "admin-auth.php";

// Database connection
require_once "../includes/common.php";


// ======================================================
// GET ORDERS
// ======================================================

$query = "
    SELECT
        orders.id,
        orders.order_id,
        orders.user_id,
        orders.total_amount,
        orders.payment_status,
        orders.order_status,
        orders.payment_method,
        orders.created_at,
        users.email
    FROM orders
    LEFT JOIN users
        ON orders.user_id = users.id
    ORDER BY orders.id DESC
";

$result = mysqli_query($con, $query);

if (!$result) {
    die(
        "Orders query failed: " .
        htmlspecialchars(mysqli_error($con))
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Orders | E-Store Admin</title>

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
            color: #111;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Arial,
                sans-serif;
        }

        .navbar-admin {
            height: 65px;
            background: #111;
            padding: 0 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            color: white;
            font-size: 22px;
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
        }

        .nav-links a {
            color: #ddd;
            text-decoration: none;
        }

        .nav-links a:hover {
            color: white;
        }

        .container-main {
            max-width: 1250px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 34px;
            font-weight: 700;
        }

        .back-btn {
            background: #0071e3;
            color: white;
            padding: 10px 18px;
            border-radius: 20px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #0077ed;
            color: white;
            text-decoration: none;
        }

        .orders-box {
            background: white;
            border-radius: 18px;
            box-shadow: 0 5px 25px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #f7f7f7;
            padding: 16px;
            text-align: left;
            font-size: 13px;
            color: #555;
            border-bottom: 1px solid #ddd;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .order-code {
            font-weight: 700;
            color: #0071e3;
        }

        .amount {
            font-weight: 700;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .completed {
            background: #d4edda;
            color: #155724;
        }

        .cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .payment {
            color: #555;
            font-size: 13px;
        }

        .date {
            color: #777;
            font-size: 13px;
        }

        .view-btn {
            display: inline-block;
            background: #111;
            color: white;
            padding: 8px 14px;
            border-radius: 18px;
            text-decoration: none;
            font-size: 13px;
        }

        .view-btn:hover {
            color: white;
            text-decoration: none;
            background: #333;
        }

        .empty {
            padding: 70px 20px;
            text-align: center;
            color: #777;
        }

        .empty i {
            font-size: 45px;
            margin-bottom: 15px;
            color: #aaa;
        }

        @media(max-width:700px) {

            .page-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 15px;
            }

            .navbar-admin {
                padding: 0 15px;
            }

            .nav-links {
                gap: 10px;
                font-size: 13px;
            }

            .logo {
                font-size: 18px;
            }

        }

    </style>

</head>

<body>


<!-- ======================================================
     ADMIN NAVBAR
====================================================== -->

<div class="navbar-admin">

    <a href="dashboard.php" class="logo">
        E-Store Admin
    </a>

    <div class="nav-links">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="products.php">
            Products
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>


<!-- ======================================================
     MAIN CONTENT
====================================================== -->

<div class="container-main">

    <div class="page-header">

        <h1>
            Customer Orders
        </h1>

        <a href="dashboard.php" class="back-btn">

            <i class="fa fa-arrow-left"></i>

            Dashboard

        </a>

    </div>


    <div class="orders-box">

        <div class="table-wrapper">


            <?php if (mysqli_num_rows($result) > 0): ?>


                <table>

                    <thead>

                        <tr>

                            <th>
                                Order ID
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Payment Status
                            </th>

                            <th>
                                Order Status
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php while ($order = mysqli_fetch_assoc($result)): ?>


                        <?php

                        // Order status
                        $status = strtolower(
                            trim($order['order_status'] ?? '')
                        );

                        $status_class = 'pending';


                        if (
                            $status === 'completed' ||
                            $status === 'delivered'
                        ) {

                            $status_class = 'completed';

                        } elseif (
                            $status === 'cancelled' ||
                            $status === 'canceled'
                        ) {

                            $status_class = 'cancelled';

                        }


                        // Payment status
                        $payment_status = strtolower(
                            trim($order['payment_status'] ?? '')
                        );

                        ?>


                        <tr>


                            <!-- Order ID -->

                            <td>

                                <div class="order-code">

                                    <?php
                                    echo htmlspecialchars(
                                        $order['order_id'] ?? ''
                                    );
                                    ?>

                                </div>

                            </td>


                            <!-- Customer -->

                            <td>

                                <?php

                                if (!empty($order['email'])) {

                                    echo htmlspecialchars(
                                        $order['email']
                                    );

                                } else {

                                    echo "User #" .
                                         (int)$order['user_id'];

                                }

                                ?>

                            </td>


                            <!-- Total -->

                            <td>

                                <span class="amount">

                                    Rs.

                                    <?php

                                    echo number_format(
                                        (float)$order['total_amount'],
                                        2
                                    );

                                    ?>

                                </span>

                            </td>


                            <!-- Payment Method -->

                            <td>

                                <span class="payment">

                                    <?php

                                    echo htmlspecialchars(
                                        $order['payment_method']
                                        ?: 'Not specified'
                                    );

                                    ?>

                                </span>

                            </td>


                            <!-- Payment Status -->

                            <td>

                                <span class="badge-status
                                    <?php

                                    echo (
                                        $payment_status === 'paid'
                                    )
                                        ? 'completed'
                                        : 'pending';

                                    ?>
                                ">

                                    <?php

                                    echo htmlspecialchars(
                                        $order['payment_status']
                                        ?? 'Pending'
                                    );

                                    ?>

                                </span>

                            </td>


                            <!-- Order Status -->

                            <td>

                                <span class="badge-status
                                    <?php
                                    echo $status_class;
                                    ?>
                                ">

                                    <?php

                                    echo htmlspecialchars(
                                        $order['order_status']
                                        ?? 'Pending'
                                    );

                                    ?>

                                </span>

                            </td>


                            <!-- Date -->

                            <td>

                                <span class="date">

                                    <?php

                                    echo htmlspecialchars(
                                        $order['created_at'] ?? ''
                                    );

                                    ?>

                                </span>

                            </td>


                            <!-- View -->

                            <td>

                                <a
                                    href="order-details.php?id=<?php echo (int)$order['id']; ?>"
                                    class="view-btn"
                                >

                                    <i class="fa fa-eye"></i>

                                    View

                                </a>

                            </td>


                        </tr>


                    <?php endwhile; ?>


                    </tbody>

                </table>


            <?php else: ?>


                <div class="empty">

                    <i class="fa fa-shopping-basket"></i>

                    <h3>
                        No Orders Yet
                    </h3>

                    <p>
                        Customer orders will appear here.
                    </p>

                </div>


            <?php endif; ?>


        </div>

    </div>

</div>


</body>

</html>