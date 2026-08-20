<?php

require("includes/common.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Check whether user has products in cart
|--------------------------------------------------------------------------
*/

$check_query = "
    SELECT id
    FROM users_items
    WHERE user_id = $user_id
    AND status = 'Added to cart'
";

$check_result = mysqli_query($con, $check_query);

if (!$check_result) {
    die("Cart check error: " . mysqli_error($con));
}

if (mysqli_num_rows($check_result) == 0) {
    header("Location: cart.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Generate unique Order ID
|--------------------------------------------------------------------------
*/

$order_id = "ORD-" . date("YmdHis") . "-" . $user_id;


/*
|--------------------------------------------------------------------------
| Confirm current user's cart
|--------------------------------------------------------------------------
*/

$update_query = "
    UPDATE users_items
    SET
        status = 'Confirmed',
        order_id = '$order_id'
    WHERE user_id = $user_id
    AND status = 'Added to cart'
";

$update_result = mysqli_query($con, $update_query);

if (!$update_result) {
    die("Order confirmation error: " . mysqli_error($con));
}


/*
|--------------------------------------------------------------------------
| Number of products confirmed
|--------------------------------------------------------------------------
*/

$items_count = mysqli_affected_rows($con);

?>

<!DOCTYPE html>

<html>

<head>

    <title>E-Store | Order Confirmed</title>

    <link rel="stylesheet"
          href="bootstrap/css/bootstrap.min.css"
          type="text/css">

    <script src="bootstrap/js/jquery-3.5.1.min.js"></script>

    <script src="bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet"
          href="css/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet"
          href="css/css.css"
          type="text/css">

    <style>

        .success-box {
            margin-top: 100px;
            padding: 35px;
            text-align: center;
        }

        .success-icon {
            font-size: 70px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .order-id {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }

    </style>

</head>


<body>

<?php include "includes/header.php"; ?>


<div class="container">

    <div class="row">

        <div class="col-sm-6 col-sm-offset-3">

            <div class="panel panel-default success-box">

                <div class="success-icon">

                    <i class="fa fa-check-circle"></i>

                </div>


                <h2>
                    Order Confirmed!
                </h2>


                <p>

                    Thank you for ordering from
                    <strong>E-Store</strong>.

                </p>


                <p>

                    Your order has been successfully confirmed.

                </p>


                <div class="order-id">

                    Order ID:
                    <br>

                    <span class="text-primary">

                        <?php echo htmlspecialchars($order_id); ?>

                    </span>

                </div>


                <p>

                    Products in this order:
                    <strong>
                        <?php echo $items_count; ?>
                    </strong>

                </p>


                <p>

                    Your order will be delivered shortly.

                </p>


                <br>


                <a href="products.php"
                   class="btn btn-primary">

                    <i class="fa fa-shopping-bag"></i>

                    Continue Shopping

                </a>


                <a href="cart.php"
                   class="btn btn-default">

                    View Cart

                </a>

            </div>

        </div>

    </div>

</div>


</body>

</html>