<?php

// ======================================================
// ADMIN LOGIN PROTECTION
// ======================================================

require_once "admin-auth.php";

// Database connection
require_once "../includes/common.php";


// ======================================================
// GET PRODUCTS
// ======================================================

$query = "
    SELECT *
    FROM products
    ORDER BY id DESC
";

$result = mysqli_query($con, $query);

if (!$result) {
    die(
        "Products query failed: " .
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

    <title>Products | E-Store Admin</title>

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
            flex-wrap: wrap;
            gap: 20px;
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
            font-size: 20px;
            font-weight: 700;
            margin-right: 10px;
        }

        .logout {
            color: #ff6b6b !important;
        }

        /* =========================
           MAIN
        ========================= */

        .container-main {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .top h2 {
            margin: 0;
            font-weight: 700;
        }

        /* =========================
           ADD BUTTON
        ========================= */

        .add-btn {
            background: #0071e3;
            color: white;
            padding: 11px 20px;
            border-radius: 22px;
            text-decoration: none;
            display: inline-block;
        }

        .add-btn:hover {
            background: #0077ed;
            color: white;
            text-decoration: none;
        }

        /* =========================
           TABLE
        ========================= */

        .table-box {
            background: white;
            border-radius: 18px;
            padding: 20px;
            overflow-x: auto;
            box-shadow: 0 5px 25px rgba(0,0,0,.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: #f7f7f7;
            border-top: none;
        }

        .table td {
            vertical-align: middle;
        }

        /* =========================
           PRODUCT IMAGE
        ========================= */

        .product-img {
            width: 65px;
            height: 65px;
            object-fit: contain;
            border-radius: 10px;
            background: #f5f5f5;
            border: 1px solid #eee;
        }

        /* =========================
           DELETE BUTTON
        ========================= */

        .delete {
            color: #d00;
            text-decoration: none;
            font-weight: 500;
        }

        .delete:hover {
            color: #a00000;
            text-decoration: none;
        }

        /* =========================
           EMPTY
        ========================= */

        .empty {
            text-align: center;
            padding: 60px 20px;
            color: #777;
        }

        .empty i {
            font-size: 45px;
            color: #aaa;
            margin-bottom: 15px;
        }

        /* =========================
           MOBILE
        ========================= */

        @media(max-width:700px) {

            .navbar-admin {
                padding: 15px;
                gap: 12px;
            }

            .logo {
                width: 100%;
            }

            .container-main {
                margin: 25px auto;
                padding: 0 12px;
            }

            .top {
                align-items: flex-start;
                flex-direction: column;
                gap: 15px;
            }

            .top h2 {
                font-size: 27px;
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
        <i class="fa fa-sign-out"></i>
        Logout
    </a>

</div>


<!-- ======================================================
     MAIN
====================================================== -->

<div class="container-main">


    <div class="top">

        <h2>
            Products
        </h2>

        <a
            href="add-product.php"
            class="add-btn"
        >

            <i class="fa fa-plus"></i>

            Add Product

        </a>

    </div>


    <div class="table-box">


        <?php if (mysqli_num_rows($result) > 0): ?>


            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Image
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php while (
                    $row = mysqli_fetch_assoc($result)
                ): ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            <?php
                            echo (int)$row['id'];
                            ?>

                        </td>


                        <!-- IMAGE -->

                        <td>

                            <img
                                class="product-img"
                                src="../img/<?php
                                    echo htmlspecialchars(
                                        $row['image'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>"
                                alt="<?php
                                    echo htmlspecialchars(
                                        $row['name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>"
                                onerror="this.style.display='none';"
                            >

                        </td>


                        <!-- NAME -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['name'],
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- PRICE -->

                        <td>

                            Rs.

                            <?php

                            echo number_format(
                                (float)$row['price'],
                                2
                            );

                            ?>

                        </td>


                        <!-- DELETE -->

                        <td>

                            <a
                                class="delete"
                                href="delete-product.php?id=<?php
                                    echo (int)$row['id'];
                                ?>"
                                onclick="
                                    return confirm(
                                        'Are you sure you want to delete this product?'
                                    );
                                "
                            >

                                <i class="fa fa-trash"></i>

                                Delete

                            </a>

                        </td>


                    </tr>


                <?php endwhile; ?>


                </tbody>

            </table>


        <?php else: ?>


            <div class="empty">

                <i class="fa fa-shopping-bag"></i>

                <h3>
                    No Products Found
                </h3>

                <p>
                    Add your first product to the store.
                </p>

            </div>


        <?php endif; ?>


    </div>

</div>


</body>

</html>