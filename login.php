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

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>E-Store | Login</title>

    <link rel="stylesheet"
          href="bootstrap/css/bootstrap.min.css"
          type="text/css">

    <script src="bootstrap/js/jquery-3.5.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet"
          href="css/font-awesome/css/font-awesome.min.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {

            margin: 0;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background: #f5f5f7;

            color: #1d1d1f;

            overflow-x: hidden;
        }


        /* =====================================================
           MAIN WRAPPER
        ===================================================== */

        .login-page {

            min-height: calc(100vh - 65px);

            padding: 35px 15px;

            display: flex;

            align-items: center;

            justify-content: center;
        }


        /* =====================================================
           MAIN CARD
        ===================================================== */

        .login-box {

            width: 100%;

            max-width: 1100px;

            min-height: 650px;

            background: white;

            border-radius: 25px;

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(0,0,0,.12);

            border: 1px solid #e7e7e7;

            animation:
                boxAppear .7s ease;
        }


        @keyframes boxAppear {

            from {

                opacity: 0;

                transform:
                    translateY(25px)
                    scale(.98);
            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }
        }


        /* =====================================================
           LEFT SIDE
        ===================================================== */

        .login-left {

            height: 650px;

            position: relative;

            overflow: hidden;

            background:
                linear-gradient(
                    145deg,
                    #020202,
                    #101010 45%,
                    #292929
                );

            color: white;

            padding: 50px 45px;
        }


        .login-left:before {

            content: "";

            position: absolute;

            width: 380px;

            height: 380px;

            border-radius: 50%;

            background:
                rgba(255,255,255,.045);

            top: -150px;

            right: -130px;
        }


        .login-left:after {

            content: "";

            position: absolute;

            width: 280px;

            height: 280px;

            border-radius: 50%;

            background:
                rgba(0,113,227,.10);

            bottom: -140px;

            left: -120px;
        }


        /* =====================================================
           BRAND
        ===================================================== */

        .brand {

            position: relative;

            z-index: 10;

            font-size: 15px;

            font-weight: 600;

            letter-spacing: 1px;

            color: #aaa;
        }


        .brand i {

            color: white;

            margin-right: 7px;
        }


        /* =====================================================
           TEXT
        ===================================================== */

        .welcome-title {

            position: relative;

            z-index: 10;

            font-size: 44px;

            line-height: 1.1;

            font-weight: 700;

            letter-spacing: -1.5px;

            margin-top: 45px;

            max-width: 440px;
        }


        .welcome-title span {

            display: block;

            color: #8ec5ff;
        }


        .welcome-text {

            position: relative;

            z-index: 10;

            color: #bdbdbd;

            font-size: 15px;

            line-height: 1.7;

            max-width: 410px;

            margin-top: 20px;
        }


        /* =====================================================
           REAL PERSON PHOTO
        ===================================================== */

        .real-person {

            position: absolute;

            width: 330px;

            height: 510px;

            right: 0;

            bottom: -10px;

            z-index: 5;

            opacity: 0;

            transform:
                translateX(-430px);

            animation:
                personEnter
                3s
                cubic-bezier(.22,.61,.36,1)
                forwards;
        }


        @keyframes personEnter {

            0% {

                opacity: 0;

                transform:
                    translateX(-430px);
            }

            15% {

                opacity: 1;
            }

            40% {

                transform:
                    translateX(-240px);
            }

            65% {

                transform:
                    translateX(-100px);
            }

            82% {

                transform:
                    translateX(15px);
            }

            92% {

                transform:
                    translateX(-8px);
            }

            100% {

                opacity: 1;

                transform:
                    translateX(0);
            }
        }


        .real-person img {

            position: absolute;

            right: 0;

            bottom: 0;

            width: 330px;

            height: 510px;

            object-fit: cover;

            object-position: center top;

            border-radius:
                170px 170px 0 0;

            filter:
                grayscale(12%)
                contrast(1.05)
                brightness(.95);

            mask-image:
                linear-gradient(
                    to bottom,
                    black 85%,
                    transparent 100%
                );

            -webkit-mask-image:
                linear-gradient(
                    to bottom,
                    black 85%,
                    transparent 100%
                );
        }


        /* =====================================================
           PERSON SHADOW
        ===================================================== */

        .person-shadow {

            position: absolute;

            width: 250px;

            height: 35px;

            background:
                rgba(0,0,0,.45);

            border-radius: 50%;

            bottom: 8px;

            right: 40px;

            filter: blur(10px);

            z-index: 3;

            animation:
                shadowMove
                3s
                ease
                forwards;
        }


        @keyframes shadowMove {

            from {

                opacity: 0;

                transform:
                    scale(.5);
            }

            to {

                opacity: 1;

                transform:
                    scale(1);
            }
        }


        /* =====================================================
           LEFT FEATURES
        ===================================================== */

        .features {

            position: absolute;

            left: 45px;

            bottom: 30px;

            z-index: 20;
        }


        .feature {

            display: inline-block;

            margin-right: 18px;

            color: #aaa;

            font-size: 11px;
        }


        .feature i {

            color: #8ec5ff;

            margin-right: 5px;
        }


        /* =====================================================
           RIGHT LOGIN
        ===================================================== */

        .login-right {

            min-height: 650px;

            padding:
                70px 60px;

            display: flex;

            align-items: center;
        }


        .login-content {

            width: 100%;

            max-width: 390px;

            margin: auto;
        }


        /* =====================================================
           LOGO
        ===================================================== */

        .login-logo {

            width: 62px;

            height: 62px;

            background: #f5f5f7;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 29px;

            color: #111;

            margin-bottom: 22px;
        }


        /* =====================================================
           TITLE
        ===================================================== */

        .login-title {

            font-size: 34px;

            font-weight: 700;

            letter-spacing: -1px;

            margin:
                0 0 8px;
        }


        .login-subtitle {

            color: #777;

            font-size: 14px;

            margin-bottom: 30px;
        }


        /* =====================================================
           ERROR
        ===================================================== */

        .login-error {

            background: #fff1f0;

            border:
                1px solid #ffd6d2;

            color: #d93025;

            padding: 12px 14px;

            border-radius: 10px;

            font-size: 13px;

            margin-bottom: 20px;

            animation:
                errorShake .4s ease;
        }


        @keyframes errorShake {

            0%,100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }


        /* =====================================================
           LABEL
        ===================================================== */

        .login-label {

            display: block;

            font-size: 13px;

            font-weight: 600;

            margin-bottom: 8px;

            color: #333;
        }


        /* =====================================================
           INPUT
        ===================================================== */

        .input-wrapper {

            position: relative;

            margin-bottom: 21px;
        }


        .input-wrapper i {

            position: absolute;

            left: 16px;

            top: 50%;

            transform:
                translateY(-50%);

            color: #999;

            font-size: 16px;

            z-index: 2;
        }


        .login-input {

            width: 100%;

            height: 50px;

            border:
                1px solid #d9d9d9;

            border-radius: 12px;

            background: #fafafa;

            padding:
                0 15px 0 45px;

            font-size: 14px;

            outline: none;

            transition:
                all .25s ease;
        }


        .login-input:focus {

            background: white;

            border-color: #0071e3;

            box-shadow:
                0 0 0 3px
                rgba(0,113,227,.10);
        }


        /* =====================================================
           PASSWORD SHOW BUTTON
        ===================================================== */

        .password-toggle {

            position: absolute;

            right: 14px;

            top: 50%;

            transform:
                translateY(-50%);

            border: none;

            background: transparent;

            color: #888;

            cursor: pointer;

            z-index: 3;
        }


        .password-toggle:hover {

            color: #0071e3;
        }


        /* =====================================================
           LOGIN BUTTON
        ===================================================== */

        .login-submit {

            width: 100%;

            height: 51px;

            border: none;

            border-radius: 26px;

            background:
                linear-gradient(
                    135deg,
                    #0071e3,
                    #005bb5
                );

            color: white;

            font-size: 15px;

            font-weight: 600;

            cursor: pointer;

            transition:
                all .25s ease;
        }


        .login-submit:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 10px 25px
                rgba(0,113,227,.25);
        }


        .login-submit:active {

            transform:
                translateY(0);
        }


        /* =====================================================
           DIVIDER
        ===================================================== */

        .divider {

            display: flex;

            align-items: center;

            gap: 12px;

            margin:
                25px 0;

            color: #999;

            font-size: 12px;
        }


        .divider:before,
        .divider:after {

            content: "";

            flex: 1;

            height: 1px;

            background: #e5e5e5;
        }


        /* =====================================================
           REGISTER
        ===================================================== */

        .register-text {

            text-align: center;

            color: #777;

            font-size: 14px;

            margin: 0;
        }


        .register-text a {

            color: #0071e3;

            font-weight: 600;

            text-decoration: none;
        }


        .register-text a:hover {

            text-decoration: underline;
        }


        /* =====================================================
           SECURITY
        ===================================================== */

        .security {

            text-align: center;

            margin-top: 25px;

            color: #999;

            font-size: 11px;
        }


        .security i {

            margin-right: 4px;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media(max-width: 991px) {

            .login-left {

                height: 500px;
            }

            .real-person {

                width: 270px;

                height: 430px;

                right: 0;
            }

            .real-person img {

                width: 270px;

                height: 430px;
            }

            .welcome-title {

                font-size: 35px;

                max-width: 330px;
            }

            .welcome-text {

                max-width: 300px;
            }

            .login-right {

                min-height: 550px;

                padding:
                    50px 40px;
            }
        }


        @media(max-width: 767px) {

            .login-page {

                padding:
                    20px 10px;
            }

            .login-box {

                border-radius: 18px;
            }

            .login-left {

                height: 400px;

                padding:
                    35px 25px;
            }

            .welcome-title {

                font-size: 29px;

                margin-top: 30px;

                max-width: 240px;
            }

            .welcome-text {

                font-size: 13px;

                max-width: 220px;
            }

            .real-person {

                width: 210px;

                height: 330px;

                right: -10px;

                bottom: -5px;
            }

            .real-person img {

                width: 210px;

                height: 330px;
            }

            .features {

                display: none;
            }

            .login-right {

                min-height: auto;

                padding:
                    40px 22px;
            }

            .login-title {

                font-size: 28px;
            }
        }


    </style>

</head>


<body>


<?php include 'includes/header.php'; ?>


<div class="login-page">


    <div class="login-box">


        <div class="row"
             style="margin:0;">


            <!-- =================================================
                 LEFT SIDE
            ================================================= -->

            <div class="col-md-6"
                 style="padding:0;">


                <div class="login-left">


                    <div class="brand">

                        <i class="fa fa-apple"></i>

                        E-STORE

                    </div>


                    <h2 class="welcome-title">

                        Welcome back.

                        <span>
                            Let's shop.
                        </span>

                    </h2>


                    <p class="welcome-text">

                        Sign in to your E-Store account
                        and continue discovering premium
                        products, great deals and fast
                        delivery.

                    </p>


                    <!-- REAL PERSON -->

                    <div class="person-shadow"></div>


                    <div class="real-person">

                        <!--
                            Real person image.
                            You can replace this URL with
                            your own image:
                            img/login-person.jpg
                        -->

                        <img
                            src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg"
                            alt="Professional person">

                    </div>


                    <!-- FEATURES -->

                    <div class="features">


                        <span class="feature">

                            <i class="fa fa-shield"></i>

                            Secure

                        </span>


                        <span class="feature">

                            <i class="fa fa-shopping-bag"></i>

                            Easy Shopping

                        </span>


                        <span class="feature">

                            <i class="fa fa-truck"></i>

                            Fast Delivery

                        </span>


                    </div>


                </div>

            </div>


            <!-- =================================================
                 RIGHT SIDE
            ================================================= -->

            <div class="col-md-6"
                 style="padding:0;">


                <div class="login-right">


                    <div class="login-content">


                        <!-- LOGO -->

                        <div class="login-logo">

                            <i class="fa fa-apple"></i>

                        </div>


                        <!-- TITLE -->

                        <h1 class="login-title">

                            Welcome Back

                        </h1>


                        <p class="login-subtitle">

                            Sign in to continue to E-Store

                        </p>


                        <!-- ERROR -->

                        <?php

                        if (isset($_GET["error"])) {

                            echo '

                            <div class="login-error">

                                <i class="fa fa-exclamation-circle"></i>

                                &nbsp;

                                ' .

                                htmlspecialchars(
                                    $_GET["error"],
                                    ENT_QUOTES,
                                    'UTF-8'
                                )

                                . '

                            </div>

                            ';

                        }

                        ?>


                        <!-- LOGIN FORM -->

                        <form
                            action="login_submit.php"
                            method="POST">


                            <!-- EMAIL -->

                            <label class="login-label">

                                Email Address

                            </label>


                            <div class="input-wrapper">


                                <i class="fa fa-envelope"></i>


                                <input
                                    type="email"
                                    class="login-input"
                                    placeholder="Enter your email"
                                    name="email"
                                    required
                                    autocomplete="email">


                            </div>


                            <!-- PASSWORD -->

                            <label class="login-label">

                                Password

                            </label>


                            <div class="input-wrapper">


                                <i class="fa fa-lock"></i>


                                <input
                                    type="password"
                                    class="login-input"
                                    placeholder="Enter your password"
                                    name="password"
                                    id="loginPassword"
                                    required
                                    autocomplete="current-password">


                                <button
                                    type="button"
                                    class="password-toggle"
                                    onclick="togglePassword()">

                                    <i
                                        class="fa fa-eye"
                                        id="eyeIcon">
                                    </i>

                                </button>


                            </div>


                            <!-- LOGIN -->

                            <button
                                type="submit"
                                class="login-submit">

                                <i class="fa fa-sign-in"></i>

                                &nbsp;

                                Login

                            </button>


                        </form>


                        <!-- DIVIDER -->

                        <div class="divider">

                            OR

                        </div>


                        <!-- REGISTER -->

                        <p class="register-text">

                            Don't have an account?

                            <a href="signup.php">

                                Create Account

                            </a>

                        </p>


                        <!-- SECURITY -->

                        <div class="security">

                            <i class="fa fa-lock"></i>

                            Your login information is securely protected.

                        </div>


                    </div>

                </div>

            </div>


        </div>

    </div>

</div>


<script>

function togglePassword() {

    var password =
        document.getElementById("loginPassword");

    var icon =
        document.getElementById("eyeIcon");


    if (password.type === "password") {

        password.type = "text";

        icon.className =
            "fa fa-eye-slash";

    } else {

        password.type = "password";

        icon.className =
            "fa fa-eye";

    }

}

</script>


</body>

</html>