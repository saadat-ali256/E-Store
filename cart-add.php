<?php

include "includes/common.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =====================================================
   LOGIN CHECK
===================================================== */

if (!isset($_SESSION['user_id'])) {

    echo "Please login first.";

    exit();
}


$user_id = (int)$_SESSION['user_id'];


/* =====================================================
   PRODUCT ID
===================================================== */

if (!isset($_GET['id'])) {

    echo "Product ID missing.";

    exit();
}


$item_id = (int)$_GET['id'];


if ($item_id <= 0) {

    echo "Invalid product.";

    exit();
}


/* =====================================================
   CHECK PRODUCT
===================================================== */

$product_query = "
    SELECT id
    FROM products
    WHERE id = $item_id
    LIMIT 1
";


$product_result =
    mysqli_query(
        $con,
        $product_query
    );


if (!$product_result) {

    echo "Product query failed: "
        . mysqli_error($con);

    exit();
}


if (
    mysqli_num_rows(
        $product_result
    ) == 0
) {

    echo "Product not found.";

    exit();
}


/* =====================================================
   ADD TO CART
===================================================== */

/*
   Important:
   We DO NOT check whether the product
   already exists.

   This means same product can be
   added multiple times.
*/

$insert_query = "
    INSERT INTO users_items
    (
        user_id,
        item_id,
        status
    )
    VALUES
    (
        $user_id,
        $item_id,
        'Added to cart'
    )
";


$insert_result =
    mysqli_query(
        $con,
        $insert_query
    );


if (!$insert_result) {

    echo "Cart insert failed: "
        . mysqli_error($con);

    exit();
}


echo "success";

?>