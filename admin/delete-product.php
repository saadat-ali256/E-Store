<?php

// ======================================================
// ADMIN LOGIN PROTECTION
// ======================================================

require_once "admin-auth.php";

// Database connection
require_once "../includes/common.php";


// ======================================================
// CHECK PRODUCT ID
// ======================================================

if (!isset($_GET['id'])) {

    header("Location: products.php");
    exit();
}


$id = (int) $_GET['id'];


if ($id <= 0) {

    header("Location: products.php");
    exit();
}


// ======================================================
// DELETE PRODUCT
// ======================================================

$query = "
    DELETE FROM products
    WHERE id = $id
    LIMIT 1
";


if (!mysqli_query($con, $query)) {

    die(
        "Product could not be deleted: " .
        htmlspecialchars(
            mysqli_error($con),
            ENT_QUOTES,
            'UTF-8'
        )
    );
}


// ======================================================
// BACK TO PRODUCTS
// ======================================================

header("Location: products.php");
exit();

?>