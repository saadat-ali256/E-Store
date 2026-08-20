<?php

require_once "admin-auth.php";
require_once "../includes/common.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | E-Store</title>

    <link rel="stylesheet"
          href="../bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="../css/font-awesome/css/font-awesome.min.css">

    <style>

        body {
            margin: 0;
            background: #f5f5f7;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Arial,
                sans-serif;
        }

        .navbar {
            background: #111;
            padding: 15px 25px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
        }

        .logout {
            float: right;
            color: #ff6b6b !important;
        }

        .container-main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            font-weight: 700;
        }

        .subtitle {
            color: #6e6e73;
            margin-bottom: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card-box {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,.05);
            text-decoration: none;
            color: #111;
            transition: .2s;
        }

        .card-box:hover {
            transform: translateY(-4px);
            text-decoration: none;
            color: #111;
        }

        .icon {
            font-size: 35px;
            margin-bottom: 15px;
            color: #0071e3;
        }

        .card-box h3 {
            margin: 0 0 8px;
            font-size: 21px;
        }

        .card-box p {
            color: #6e6e73;
            margin: 0;
        }

        @media(max-width:800px) {

            .cards {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <div class="navbar">

        <a href="dashboard.php" class="logo">
            E-Store Admin
        </a>

        <a href="logout.php" class="logout">
            <i class="fa fa-sign-out"></i>
            Logout
        </a>

    </div>


    <div class="container-main">

        <h1>
            Admin Dashboard
        </h1>

        <div class="subtitle">
            Welcome to E-Store administration panel.
        </div>


        <div class="cards">


            <!-- Products -->

            <a href="products.php" class="card-box">

                <div class="icon">
                    <i class="fa fa-shopping-bag"></i>
                </div>

                <h3>
                    Products
                </h3>

                <p>
                    Add, view and delete products.
                </p>

            </a>


            <!-- Add Product -->

            <a href="add-product.php" class="card-box">

                <div class="icon">
                    <i class="fa fa-plus-circle"></i>
                </div>

                <h3>
                    Add Product
                </h3>

                <p>
                    Add a new product to your store.
                </p>

            </a>


            <!-- Orders -->

            <a href="orders.php" class="card-box">

                <div class="icon">
                    <i class="fa fa-list-alt"></i>
                </div>

                <h3>
                    Orders
                </h3>

                <p>
                    View customer orders.
                </p>

            </a>


        </div>

    </div>

</body>

</html>