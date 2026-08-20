<?php

include "includes/common.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();
}


$user_id =
    (int)$_SESSION['user_id'];


if (!isset($_GET['id'])) {

    header("Location: cart.php");

    exit();
}


$cart_id =
    (int)$_GET['id'];


if ($cart_id <= 0) {

    header("Location: cart.php");

    exit();
}


/* =====================================================
   DELETE ONLY USER'S OWN CART ITEM
===================================================== */

$query = "
    DELETE FROM users_items

    WHERE id = $cart_id

    AND user_id = $user_id

    AND status = 'Added to cart'
";


$result =
    mysqli_query(
        $con,
        $query
    );


if (!$result) {

    die(
        "Remove failed: "
        . mysqli_error($con)
    );

}


header("Location: cart.php");

exit();

?>