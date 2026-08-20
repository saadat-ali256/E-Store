<?php
require("includes/common.php");

if (isset($_SESSION['email'])) {
    header('location: products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Apple Store | Premium Apple Products</title>

    <link rel="stylesheet"
          href="bootstrap/css/bootstrap.min.css"
          type="text/css">

    <script type="text/javascript"
            src="bootstrap/js/jquery-3.5.1.min.js"></script>

    <script type="text/javascript"
            src="bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet"
          href="css/font-awesome/css/font-awesome.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
                         Roboto, Arial, sans-serif;
            background: #000;
            color: #fff;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            padding: 20px 0;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(12px);
        }

        .logo {
            font-size: 25px;
            font-weight: 700;
            color: #fff !important;
            text-decoration: none;
        }

        .logo i {
            margin-right: 8px;
        }

        .nav-buttons a {
            margin-left: 10px;
            padding: 9px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .login-btn {
            color: #fff;
            border: 1px solid rgba(255,255,255,0.5);
        }

        .login-btn:hover {
            background: #fff;
            color: #000;
        }

        .signup-btn {
            background: #fff;
            color: #000;
        }

        .signup-btn:hover {
            background: #ddd;
            color: #000;
        }

        /* Hero */
        .hero {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;

            background:
                linear-gradient(
                    rgba(0,0,0,0.48),
                    rgba(0,0,0,0.75)
                ),
                url("images/banner.jpg");

            background-size: cover;
            background-position: center;
        }

        /* If banner image doesn't exist */
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(
                    circle at center,
                    rgba(255,255,255,0.08),
                    transparent 45%
                );
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 850px;
            padding: 30px;
        }

        .small-title {
            font-size: 18px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #cfcfcf;
            margin-bottom: 18px;
        }

        .hero h1 {
            font-size: 75px;
            font-weight: 700;
            letter-spacing: -3px;
            margin-bottom: 20px;
        }

        .hero h1 span {
            background: linear-gradient(
                90deg,
                #ffffff,
                #aaa
            );
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 21px;
            color: #d2d2d2;
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .main-btn {
            padding: 14px 32px;
            border-radius: 30px;
            font-size: 17px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-main {
            background: #fff;
            color: #000;
        }

        .login-main:hover {
            background: #ddd;
            color: #000;
            transform: translateY(-3px);
        }

        .signup-main {
            border: 1px solid rgba(255,255,255,0.6);
            color: #fff;
        }

        .signup-main:hover {
            background: #fff;
            color: #000;
            transform: translateY(-3px);
        }

        /* Features */
        .features {
            position: absolute;
            bottom: 35px;
            left: 0;
            width: 100%;
            z-index: 3;
        }

        .feature-box {
            color: #ddd;
            font-size: 14px;
            text-align: center;
        }

        .feature-box i {
            font-size: 22px;
            margin-bottom: 8px;
            display: block;
        }

        /* Mobile */
        @media (max-width: 768px) {

            .hero h1 {
                font-size: 48px;
                letter-spacing: -2px;
            }

            .hero p {
                font-size: 17px;
            }

            .logo {
                font-size: 20px;
            }

            .nav-buttons a {
                padding: 7px 14px;
                font-size: 13px;
            }

            .features {
                display: none;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar-custom">
    <div class="container">
        <div class="row">

            <div class="col-xs-6">
                <a href="index.php" class="logo">
                    <i class="fa fa-apple"></i> E Store
                </a>
            </div>

            <div class="col-xs-6 text-right nav-buttons">

                <a href="login.php" class="login-btn">
                    <i class="fa fa-sign-in"></i> Login
                </a>

                <a href="signup.php" class="signup-btn">
                    <i class="fa fa-user-plus"></i> Sign Up
                </a>

            </div>

        </div>
    </div>
</nav>


<!-- HERO SECTION -->
<section class="hero">

    <div class="hero-content">

        <div class="small-title">
            Welcome to the Future
        </div>

        <h1>
            Think Different.<br>
            <span>Shop Apple.</span>
        </h1>

        <p>
            Discover the latest Apple products with a premium
            shopping experience designed for you.
        </p>

        <div class="hero-buttons">

            <a href="login.php" class="main-btn login-main">
                <i class="fa fa-sign-in"></i>
                Login
            </a>

            <a href="signup.php" class="main-btn signup-main">
                <i class="fa fa-user-plus"></i>
                Create Account
            </a>

        </div>

    </div>


    <!-- FEATURES -->
    <div class="features">

        <div class="container">

            <div class="row">

                <div class="col-sm-4 feature-box">
                    <i class="fa fa-shield"></i>
                    Secure Shopping
                </div>

                <div class="col-sm-4 feature-box">
                    <i class="fa fa-truck"></i>
                    Fast Delivery
                </div>

                <div class="col-sm-4 feature-box">
                    <i class="fa fa-star"></i>
                    Premium Products
                </div>

            </div>

        </div>

    </div>

</section>


</body>
</html>