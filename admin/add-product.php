<?php

// ======================================================
// ADMIN LOGIN PROTECTION
// ======================================================

require_once "admin-auth.php";

// Database connection
require_once "../includes/common.php";


$message = "";
$error = "";


// ======================================================
// ADD PRODUCT
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $image = trim($_POST['image'] ?? '');


    // Check empty fields
    if (
        $name === '' ||
        $price === '' ||
        $image === ''
    ) {

        $error = "Please fill all fields.";

    }

    // Check price
    elseif (
        !is_numeric($price) ||
        (float)$price < 0
    ) {

        $error = "Please enter a valid price.";

    }

    else {

        // Secure database values
        $name_db = mysqli_real_escape_string(
            $con,
            $name
        );

        $image_db = mysqli_real_escape_string(
            $con,
            $image
        );

        $price_db = (float)$price;


        // Insert product
        $query = "
            INSERT INTO products
            (name, price, image)
            VALUES
            ('$name_db', $price_db, '$image_db')
        ";


        if (mysqli_query($con, $query)) {

            $message = "Product added successfully.";

            // Form clear karne ke liye values empty
            $name = "";
            $price = "";
            $image = "";

        } else {

            $error =
                "Product could not be added: " .
                mysqli_error($con);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Product | E-Store Admin</title>

    <link rel="stylesheet"
          href="../bootstrap/css/bootstrap.min.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f5f7;
            font-family: Arial, sans-serif;
            color: #111;
        }


        /* =========================
           NAVBAR
        ========================= */

        .navbar-admin {
            background: #111;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .navbar-admin a {
            color: white;
            text-decoration: none;
        }

        .navbar-admin a:hover {
            color: #ddd;
            text-decoration: none;
        }

        .logo {
            font-size: 21px;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .logout {
            color: #ff6b6b !important;
        }


        /* =========================
           MAIN BOX
        ========================= */

        .box {
            max-width: 600px;
            margin: 55px auto;
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,.06);
        }

        .box h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-weight: 700;
        }


        /* =========================
           FORM
        ========================= */

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .form-control:focus {
            border-color: #0071e3;
            box-shadow: 0 0 0 2px rgba(0,113,227,.12);
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: #6e6e73;
            font-size: 13px;
        }


        /* =========================
           ADD BUTTON
        ========================= */

        .btn-add {
            width: 100%;
            border: none;
            background: #0071e3;
            color: white;
            height: 48px;
            border-radius: 24px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-add:hover {
            background: #0077ed;
        }


        /* =========================
           SUCCESS / ERROR
        ========================= */

        .success {
            background: #dff6e5;
            color: #187a35;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .error {
            background: #ffe3e3;
            color: #b00000;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }


        /* =========================
           BACK LINK
        ========================= */

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #0071e3;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            color: #005bb5;
            text-decoration: none;
        }


        /* =========================
           MOBILE
        ========================= */

        @media(max-width:650px) {

            .navbar-admin {
                padding: 15px;
            }

            .nav-links {
                width: 100%;
                gap: 15px;
                font-size: 14px;
            }

            .box {
                margin: 30px 15px;
                padding: 25px 20px;
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

        <a href="orders.php">
            Orders
        </a>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>

</div>


<!-- ======================================================
     ADD PRODUCT FORM
====================================================== -->

<div class="box">

    <h2>
        Add Product
    </h2>


    <?php if ($message !== ""): ?>

        <div class="success">

            <?php
            echo htmlspecialchars(
                $message,
                ENT_QUOTES,
                'UTF-8'
            );
            ?>

        </div>

    <?php endif; ?>


    <?php if ($error !== ""): ?>

        <div class="error">

            <?php
            echo htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            );
            ?>

        </div>

    <?php endif; ?>


    <form method="POST" action="">


        <!-- Product Name -->

        <label for="name">
            Product Name
        </label>

        <input
            type="text"
            id="name"
            name="name"
            class="form-control"
            value="<?php
                echo htmlspecialchars(
                    $name,
                    ENT_QUOTES,
                    'UTF-8'
                );
            ?>"
            placeholder="Enter product name"
            required
        >

        <br>


        <!-- Price -->

        <label for="price">
            Price
        </label>

        <input
            type="number"
            id="price"
            name="price"
            class="form-control"
            value="<?php
                echo htmlspecialchars(
                    $price,
                    ENT_QUOTES,
                    'UTF-8'
                );
            ?>"
            step="0.01"
            min="0"
            placeholder="Enter product price"
            required
        >

        <br>


        <!-- Image -->

        <label for="image">
            Image File Name
        </label>

        <input
            type="text"
            id="image"
            name="image"
            class="form-control"
            value="<?php
                echo htmlspecialchars(
                    $image,
                    ENT_QUOTES,
                    'UTF-8'
                );
            ?>"
            placeholder="iphone.jpg"
            required
        >

        <small class="help-text">
            Image ko project ke
            <b>img</b>
            folder mein rakhein.
        </small>

        <br><br>


        <!-- Submit -->

        <button
            type="submit"
            class="btn-add"
        >

            Add Product

        </button>


    </form>


    <a
        href="products.php"
        class="back-link"
    >

        ← Back to Products

    </a>

</div>


</body>

</html>