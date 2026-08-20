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
   GET FORM DATA
========================= */

$full_name   = trim($_POST['full_name'] ?? '');
$email       = trim($_POST['email'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$address     = trim($_POST['address'] ?? '');
$city        = trim($_POST['city'] ?? '');
$postal_code = trim($_POST['postal_code'] ?? '');

$payment_method = trim(
    $_POST['payment_method'] ?? 'Cash on Delivery'
);


/* =========================
   VALIDATION
========================= */

if (
    $full_name === '' ||
    $email === '' ||
    $phone === '' ||
    $address === '' ||
    $city === ''
) {

    die("Please fill all required delivery information.");
}


/* =========================
   CARD LAST 4
========================= */

$card_last4 = '';

if ($payment_method === 'Card') {

    $card_number = preg_replace(
        '/\D/',
        '',
        $_POST['card_number'] ?? ''
    );

    $card_name = trim(
        $_POST['card_name'] ?? ''
    );

    $card_expiry = trim(
        $_POST['card_expiry'] ?? ''
    );

    $card_cvv = trim(
        $_POST['card_cvv'] ?? ''
    );


    if (
        $card_name === '' ||
        $card_number === '' ||
        $card_expiry === '' ||
        $card_cvv === ''
    ) {

        die("Please enter complete card details.");
    }


    if (strlen($card_number) < 12) {

        die("Invalid card number.");
    }


    /* Only save last 4 digits */
    $card_last4 = substr(
        $card_number,
        -4
    );
}


/*
IMPORTANT:
We do NOT store the complete card number,
CVV or expiry in the database.
*/


/* =========================
   GET CART
========================= */

$cart_query = "
    SELECT
        users_items.item_id,
        products.name,
        products.price
    FROM users_items

    INNER JOIN products
        ON users_items.item_id = products.id

    WHERE users_items.user_id = $user_id
    AND users_items.status = 'Added to cart'

    ORDER BY users_items.id ASC
";


$cart_result = mysqli_query(
    $con,
    $cart_query
);


if (!$cart_result) {

    die(
        "Cart query failed: " .
        htmlspecialchars(
            mysqli_error($con)
        )
    );
}


/* =========================
   GROUP CART PRODUCTS
========================= */

$products = array();

$total_amount = 0;


while (
    $row = mysqli_fetch_assoc(
        $cart_result
    )
) {

    $item_id = (int)$row['item_id'];


    if (
        !isset(
            $products[$item_id]
        )
    ) {

        $products[$item_id] = array(

            'item_id' => $item_id,

            'product_name' =>
                $row['name'],

            'price' =>
                (float)$row['price'],

            'quantity' => 0
        );
    }


    $products[$item_id]['quantity']++;
}


/* =========================
   EMPTY CART CHECK
========================= */

if (empty($products)) {

    die("Your cart is empty.");
}


/* =========================
   CALCULATE TOTAL
========================= */

foreach ($products as $product) {

    $total_amount +=
        $product['price'] *
        $product['quantity'];
}


/* =========================
   CREATE ORDER ID
========================= */

$order_id =
    'ORD-' .
    date('Ymd-His') .
    '-' .
    strtoupper(
        substr(
            md5(
                uniqid(
                    (string)mt_rand(),
                    true
                )
            ),
            0,
            6
        )
    );


/* =========================
   PAYMENT STATUS
========================= */

if ($payment_method === 'Card') {

    $payment_status = 'Paid';

} else {

    $payment_status = 'Pending';
}


/* =========================
   ORDER STATUS
========================= */

$order_status = 'Pending';


/* =========================
   ESCAPE DATA
========================= */

$order_id_db =
    mysqli_real_escape_string(
        $con,
        $order_id
    );

$full_name_db =
    mysqli_real_escape_string(
        $con,
        $full_name
    );

$email_db =
    mysqli_real_escape_string(
        $con,
        $email
    );

$phone_db =
    mysqli_real_escape_string(
        $con,
        $phone
    );

$address_db =
    mysqli_real_escape_string(
        $con,
        $address
    );

$city_db =
    mysqli_real_escape_string(
        $con,
        $city
    );

$postal_code_db =
    mysqli_real_escape_string(
        $con,
        $postal_code
    );

$payment_method_db =
    mysqli_real_escape_string(
        $con,
        $payment_method
    );

$payment_status_db =
    mysqli_real_escape_string(
        $con,
        $payment_status
    );

$order_status_db =
    mysqli_real_escape_string(
        $con,
        $order_status
    );

$card_last4_db =
    mysqli_real_escape_string(
        $con,
        $card_last4
    );


/* =========================
   START TRANSACTION
========================= */

mysqli_begin_transaction($con);


try {


    /* =========================
       INSERT ORDER
    ========================= */

    $order_query = "

        INSERT INTO orders (

            order_id,
            user_id,

            full_name,
            email,
            phone,
            address,
            city,
            postal_code,

            total_amount,

            payment_status,
            order_status,
            payment_method,

            card_last4,

            created_at

        )

        VALUES (

            '$order_id_db',
            $user_id,

            '$full_name_db',
            '$email_db',
            '$phone_db',
            '$address_db',
            '$city_db',
            '$postal_code_db',

            $total_amount,

            '$payment_status_db',
            '$order_status_db',
            '$payment_method_db',

            '$card_last4_db',

            NOW()

        )
    ";


    if (
        !mysqli_query(
            $con,
            $order_query
        )
    ) {

        throw new Exception(
            "Order insert failed: " .
            mysqli_error($con)
        );
    }


    /* =========================
       GET NEW ORDER DATABASE ID
    ========================= */

    $new_order_db_id =
        mysqli_insert_id($con);


    /* =========================
       INSERT ORDER ITEMS
    ========================= */

    foreach (
        $products as $product
    ) {

        $product_id =
            (int)$product['item_id'];

        $product_name =
            mysqli_real_escape_string(
                $con,
                $product['product_name']
            );

        $price =
            (float)$product['price'];

        $quantity =
            (int)$product['quantity'];

        $subtotal =
            $price * $quantity;


        $item_query = "

            INSERT INTO order_items (

                order_id,
                product_id,
                product_name,
                price,
                quantity,
                subtotal,
                created_at

            )

            VALUES (

                $new_order_db_id,
                $product_id,
                '$product_name',
                $price,
                $quantity,
                $subtotal,
                NOW()

            )
        ";


        if (
            !mysqli_query(
                $con,
                $item_query
            )
        ) {

            throw new Exception(
                "Order item insert failed: " .
                mysqli_error($con)
            );
        }
    }


    /* =========================
       REMOVE CART ITEMS
    ========================= */

    $cart_update = "

        UPDATE users_items

        SET status = 'Purchased'

        WHERE user_id = $user_id

        AND status = 'Added to cart'

    ";


    if (
        !mysqli_query(
            $con,
            $cart_update
        )
    ) {

        throw new Exception(
            "Cart update failed: " .
            mysqli_error($con)
        );
    }


    /* =========================
       COMMIT
    ========================= */

    mysqli_commit($con);


    /* =========================
       REDIRECT
    ========================= */

    header(
        "Location: order-success.php?order_id=" .
        $new_order_db_id
    );

    exit();


} catch (Exception $e) {


    /* =========================
       ROLLBACK
    ========================= */

    mysqli_rollback($con);


    die(
        "<h2>Order Failed</h2>" .
        "<p>" .
        htmlspecialchars(
            $e->getMessage()
        ) .
        "</p>" .
        "<p><a href='checkout.php'>Go Back to Checkout</a></p>"
    );
}
mysqli_commit($con);

header(
    "Location: order-success.php?order_id=" .
    urlencode($order_id)
);

exit();

?>