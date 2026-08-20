<?php

require("includes/common.php");

if (isset($_SESSION['user_id'])) {
    header("Location: products.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>E-Store | Create Account</title>

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
        }


        /* =====================================================
           MAIN PAGE
        ===================================================== */

        .signup-page {

            min-height: calc(100vh - 62px);

            padding: 35px 15px;

            display: flex;

            align-items: center;
        }


        .signup-box {

            max-width: 1150px;

            width: 100%;

            margin: auto;

            background: #fff;

            border-radius: 25px;

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(0,0,0,.12);

            border: 1px solid #e7e7e7;
        }


        /* =====================================================
           LEFT SIDE
        ===================================================== */

        .signup-left {

            min-height: 680px;

            position: relative;

            overflow: hidden;

            background:
                linear-gradient(
                    145deg,
                    #020202 0%,
                    #101010 45%,
                    #252525 100%
                );

            color: white;

            padding: 55px 45px;
        }


        .signup-left:before {

            content: "";

            position: absolute;

            width: 350px;

            height: 350px;

            border-radius: 50%;

            background:
                rgba(255,255,255,.045);

            top: -140px;

            right: -120px;
        }


        .signup-left:after {

            content: "";

            position: absolute;

            width: 260px;

            height: 260px;

            border-radius: 50%;

            background:
                rgba(0,113,227,.10);

            bottom: -130px;

            left: -100px;
        }


        /* =====================================================
           LEFT TEXT
        ===================================================== */

        .brand-mini {

            position: relative;

            z-index: 10;

            font-size: 15px;

            font-weight: 600;

            letter-spacing: 1px;

            color: #aaa;
        }


        .brand-mini i {

            color: #fff;

            margin-right: 7px;
        }


        .left-title {

            position: relative;

            z-index: 10;

            font-size: 43px;

            line-height: 1.1;

            font-weight: 700;

            letter-spacing: -1.5px;

            margin-top: 45px;

            margin-bottom: 18px;

            max-width: 450px;
        }


        .left-title span {

            display: block;

            color: #8ec5ff;
        }


        .left-text {

            position: relative;

            z-index: 10;

            color: #bdbdbd;

            font-size: 15px;

            line-height: 1.7;

            max-width: 420px;
        }


        /* =====================================================
           PERSON
        ===================================================== */

        .person-area {

            position: absolute;

            bottom: -20px;

            right: 20px;

            width: 300px;

            height: 500px;

            z-index: 5;

            transform:
                translateX(-450px);

            animation:

                personWalkIn
                3.2s
                cubic-bezier(.22,.61,.36,1)
                forwards,

                personIdle
                3s
                ease-in-out
                3.2s
                infinite;
        }


        /* =====================================================
           PERSON WALK ANIMATION
        ===================================================== */

        @keyframes personWalkIn {

            0% {

                transform:
                    translateX(-450px);

                opacity: 0;
            }

            12% {

                opacity: 1;
            }

            30% {

                transform:
                    translateX(-300px);
            }

            50% {

                transform:
                    translateX(-170px);
            }

            70% {

                transform:
                    translateX(-70px);
            }

            85% {

                transform:
                    translateX(15px);
            }

            94% {

                transform:
                    translateX(-8px);
            }

            100% {

                transform:
                    translateX(0);

                opacity: 1;
            }

        }


        @keyframes personIdle {

            0%,
            100% {

                transform:
                    translateY(0);
            }

            50% {

                transform:
                    translateY(-5px);
            }

        }


        /* =====================================================
           HEAD
        ===================================================== */

        .person-head {

            position: absolute;

            width: 92px;

            height: 105px;

            background: #c98d6b;

            border-radius:
                45% 45% 48% 48%;

            top: 28px;

            left: 104px;

            z-index: 5;

            animation:
                headMove
                1.2s
                ease-in-out
                3.2s
                infinite;
        }


        @keyframes headMove {

            0%,
            100% {
                transform: rotate(0deg);
            }

            50% {
                transform: rotate(1deg);
            }

        }


        /* =====================================================
           HAIR
        ===================================================== */

        .person-hair {

            position: absolute;

            width: 98px;

            height: 57px;

            background: #080808;

            border-radius:
                55px 55px 20px 20px;

            top: 16px;

            left: 101px;

            z-index: 6;
        }


        /* =====================================================
           EARS
        ===================================================== */

        .person-ear {

            position: absolute;

            width: 15px;

            height: 28px;

            background: #c98d6b;

            border-radius: 50%;

            top: 72px;

            z-index: 4;
        }


        .person-ear.left {
            left: 97px;
        }


        .person-ear.right {
            left: 188px;
        }


        /* =====================================================
           EYES
        ===================================================== */

        .person-eye {

            position: absolute;

            width: 7px;

            height: 7px;

            background: #222;

            border-radius: 50%;

            top: 78px;

            z-index: 7;
        }


        .person-eye.left {
            left: 129px;
        }


        .person-eye.right {
            left: 165px;
        }


        /* =====================================================
           SMILE
        ===================================================== */

        .person-smile {

            position: absolute;

            width: 30px;

            height: 12px;

            border-bottom:
                2px solid #6b3c2c;

            border-radius:
                0 0 30px 30px;

            top: 103px;

            left: 135px;

            z-index: 7;
        }


        /* =====================================================
           NECK
        ===================================================== */

        .person-neck {

            position: absolute;

            width: 40px;

            height: 40px;

            background: #b97858;

            top: 115px;

            left: 131px;

            z-index: 3;
        }


        /* =====================================================
           BODY
        ===================================================== */

        .person-body {

            position: absolute;

            width: 190px;

            height: 245px;

            background:
                linear-gradient(
                    135deg,
                    #050505,
                    #181818
                );

            border-radius:
                45px 45px 10px 10px;

            top: 145px;

            left: 55px;

            z-index: 2;

            box-shadow:
                0 10px 25px rgba(0,0,0,.4);
        }


        /* =====================================================
           SHIRT
        ===================================================== */

        .person-shirt {

            position: absolute;

            width: 65px;

            height: 125px;

            background: #f8f8f8;

            top: 150px;

            left: 118px;

            z-index: 3;

            clip-path:
                polygon(
                    20% 0,
                    80% 0,
                    100% 100%,
                    0 100%
                );
        }


        /* =====================================================
           TIE
        ===================================================== */

        .person-tie {

            position: absolute;

            width: 18px;

            height: 90px;

            background:
                linear-gradient(
                    90deg,
                    #111,
                    #333,
                    #111
                );

            top: 158px;

            left: 142px;

            z-index: 4;

            clip-path:
                polygon(
                    25% 0,
                    75% 0,
                    100% 85%,
                    50% 100%,
                    0 85%
                );
        }


        /* =====================================================
           ARMS
        ===================================================== */

        .person-arm {

            position: absolute;

            width: 55px;

            height: 190px;

            background:
                linear-gradient(
                    90deg,
                    #050505,
                    #1b1b1b,
                    #050505
                );

            border-radius: 30px;

            top: 165px;

            z-index: 1;

            transform-origin:
                top center;
        }


        .person-arm.left {

            left: 30px;

            transform:
                rotate(18deg);

            animation:
                leftArmWalk
                .65s
                ease-in-out
                0s
                4
                alternate;
        }


        .person-arm.right {

            right: 30px;

            transform:
                rotate(-18deg);

            animation:
                rightArmWalk
                .65s
                ease-in-out
                0s
                4
                alternate;
        }


        @keyframes leftArmWalk {

            from {
                transform: rotate(25deg);
            }

            to {
                transform: rotate(-15deg);
            }

        }


        @keyframes rightArmWalk {

            from {
                transform: rotate(-25deg);
            }

            to {
                transform: rotate(15deg);
            }

        }


        /* =====================================================
           HANDS
        ===================================================== */

        .person-hand {

            position: absolute;

            width: 38px;

            height: 55px;

            background: #c98d6b;

            border-radius: 20px;

            bottom: 0;

            z-index: 4;
        }


        .person-hand.left {
            left: 29px;
        }


        .person-hand.right {
            right: 29px;
        }


        /* =====================================================
           LEGS
        ===================================================== */

        .person-leg {

            position: absolute;

            width: 62px;

            height: 165px;

            background:
                linear-gradient(
                    90deg,
                    #050505,
                    #171717,
                    #050505
                );

            border-radius:
                20px 20px 12px 12px;

            top: 350px;

            z-index: 1;

            transform-origin:
                top center;
        }


        .person-leg.left {

            left: 82px;

            animation:
                leftLegWalk
                .65s
                ease-in-out
                0s
                4
                alternate;
        }


        .person-leg.right {

            left: 153px;

            animation:
                rightLegWalk
                .65s
                ease-in-out
                0s
                4
                alternate;
        }


        @keyframes leftLegWalk {

            from {
                transform: rotate(12deg);
            }

            to {
                transform: rotate(-12deg);
            }

        }


        @keyframes rightLegWalk {

            from {
                transform: rotate(-12deg);
            }

            to {
                transform: rotate(12deg);
            }

        }


        /* =====================================================
           SHOES
        ===================================================== */

        .person-shoe {

            position: absolute;

            width: 78px;

            height: 28px;

            background: #020202;

            border-radius:
                25px 25px 10px 10px;

            z-index: 2;

            top: 490px;

            box-shadow:
                0 4px 8px rgba(0,0,0,.5);
        }


        .person-shoe.left {
            left: 68px;
        }


        .person-shoe.right {
            left: 150px;
        }


        /* =====================================================
           FEATURES
        ===================================================== */

        .left-features {

            position: absolute;

            left: 45px;

            bottom: 35px;

            z-index: 10;
        }


        .left-feature {

            display: inline-block;

            margin-right: 20px;

            color: #aaa;

            font-size: 11px;
        }


        .left-feature i {

            color: #8ec5ff;

            margin-right: 5px;
        }


        /* =====================================================
           RIGHT SIDE
        ===================================================== */

        .signup-right {

            min-height: 680px;

            padding: 45px 55px;

            background: #fff;
        }


        .signup-heading {

            font-size: 32px;

            font-weight: 700;

            letter-spacing: -1px;

            margin:
                0 0 7px;
        }


        .signup-subheading {

            color: #777;

            font-size: 14px;

            margin-bottom: 30px;
        }


        /* =====================================================
           ERROR
        ===================================================== */

        .signup-error {

            background: #fff1f0;

            border:
                1px solid #ffd6d2;

            color: #d93025;

            border-radius: 10px;

            padding: 11px 14px;

            margin-bottom: 18px;

            font-size: 13px;
        }


        .field-error {

            color: #d93025;

            font-size: 12px;

            margin-top: 5px;
        }


        /* =====================================================
           FORM
        ===================================================== */

        .signup-form .form-group {

            margin-bottom: 17px;
        }


        .signup-form label {

            font-size: 12px;

            font-weight: 600;

            color: #333;

            margin-bottom: 7px;
        }


        .input-box {

            position: relative;
        }


        .input-box i {

            position: absolute;

            left: 15px;

            top: 50%;

            transform:
                translateY(-50%);

            color: #999;

            z-index: 2;
        }


        .signup-input {

            height: 45px;

            width: 100%;

            border-radius: 10px;

            border:
                1px solid #ddd;

            background: #fafafa;

            padding:
                0 14px 0 42px;

            font-size: 13px;

            outline: none;

            transition:
                all .25s ease;
        }


        .signup-input:focus {

            background: white;

            border-color: #0071e3;

            box-shadow:
                0 0 0 3px
                rgba(0,113,227,.09);
        }


        /* =====================================================
           TWO COLUMNS
        ===================================================== */

        .form-row {

            display: flex;

            gap: 15px;
        }


        .form-half {

            width: 50%;
        }


        /* =====================================================
           BUTTONS
        ===================================================== */

        .signup-actions {

            display: flex;

            gap: 10px;

            margin-top: 25px;
        }


        .create-btn {

            flex: 1;

            height: 47px;

            border: none;

            border-radius: 24px;

            background: #0071e3;

            color: white;

            font-size: 14px;

            font-weight: 600;

            transition:
                all .25s ease;
        }


        .create-btn:hover {

            background: #005bb5;

            transform:
                translateY(-2px);

            box-shadow:
                0 8px 20px
                rgba(0,113,227,.22);
        }


        .login-btn {

            height: 47px;

            padding:
                0 25px;

            border-radius: 24px;

            background: white;

            border:
                1px solid #ddd;

            color: #333;

            font-size: 14px;

            font-weight: 600;

            display: flex;

            align-items: center;

            justify-content: center;

            text-decoration: none !important;

            transition:
                all .25s ease;
        }


        .login-btn:hover {

            border-color: #0071e3;

            color: #0071e3;

            background: #f8fbff;
        }


        /* =====================================================
           SECURITY
        ===================================================== */

        .signup-security {

            text-align: center;

            margin-top: 22px;

            color: #999;

            font-size: 11px;
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media(max-width: 991px) {

            .signup-left {

                min-height: 500px;
            }


            .person-area {

                transform:
                    scale(.75)
                    translateX(-450px);

                transform-origin:
                    bottom right;
            }


            .left-title {

                font-size: 35px;
            }

        }


        @media(max-width: 767px) {

            .signup-page {

                padding:
                    20px 10px;
            }


            .signup-left {

                min-height: 390px;

                padding:
                    35px 25px;
            }


            .left-title {

                font-size: 29px;

                margin-top: 30px;

                max-width: 250px;
            }


            .left-text {

                max-width: 220px;

                font-size: 13px;
            }


            .person-area {

                transform:
                    scale(.55)
                    translateX(-350px);

                right: -30px;

                bottom: -70px;

                transform-origin:
                    bottom right;
            }


            .left-features {

                display: none;
            }


            .signup-right {

                min-height: auto;

                padding:
                    35px 22px;
            }


            .signup-heading {

                font-size: 27px;
            }


            .form-row {

                display: block;
            }


            .form-half {

                width: 100%;
            }


            .signup-actions {

                display: block;
            }


            .create-btn {

                width: 100%;

                margin-bottom: 10px;
            }


            .login-btn {

                width: 100%;
            }

        }

    </style>

</head>


<body>


<?php include "includes/header.php"; ?>


<div class="signup-page">

    <div class="signup-box">

        <div class="row" style="margin:0;">


            <!-- =================================================
                 LEFT SIDE
            ================================================= -->

            <div class="col-md-6"
                 style="padding:0;">

                <div class="signup-left">


                    <div class="brand-mini">

                        <i class="fa fa-apple"></i>

                        E-STORE

                    </div>


                    <h2 class="left-title">

                        Your shopping journey

                        <span>
                            starts here.
                        </span>

                    </h2>


                    <p class="left-text">

                        Create your account and discover
                        premium products, simple shopping,
                        secure checkout and fast delivery.

                    </p>


                    <!-- =================================================
                         WALKING PERSON
                    ================================================= -->

                    <div class="person-area">


                        <div class="person-hair"></div>


                        <div class="person-ear left"></div>

                        <div class="person-ear right"></div>


                        <div class="person-head"></div>


                        <div class="person-eye left"></div>

                        <div class="person-eye right"></div>


                        <div class="person-smile"></div>


                        <div class="person-neck"></div>


                        <div class="person-body"></div>


                        <div class="person-shirt"></div>


                        <div class="person-tie"></div>


                        <div class="person-arm left"></div>

                        <div class="person-arm right"></div>


                        <div class="person-hand left"></div>

                        <div class="person-hand right"></div>


                        <div class="person-leg left"></div>

                        <div class="person-leg right"></div>


                        <div class="person-shoe left"></div>

                        <div class="person-shoe right"></div>


                    </div>


                    <!-- =================================================
                         FEATURES
                    ================================================= -->

                    <div class="left-features">


                        <span class="left-feature">

                            <i class="fa fa-shield"></i>

                            Secure

                        </span>


                        <span class="left-feature">

                            <i class="fa fa-shopping-bag"></i>

                            Easy Shopping

                        </span>


                        <span class="left-feature">

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


                <div class="signup-right">


                    <h1 class="signup-heading">

                        Create Account

                    </h1>


                    <p class="signup-subheading">

                        Join E-Store and start shopping today.

                    </p>


                    <!-- ERROR -->

                    <?php

                    if (isset($_GET['error'])) {

                        echo '
                        <div class="signup-error">

                            <i class="fa fa-exclamation-circle"></i>

                            &nbsp;'

                            .

                            htmlspecialchars(
                                $_GET['error'],
                                ENT_QUOTES,
                                'UTF-8'
                            )

                            .

                        '</div>';

                    }

                    ?>


                    <form
                        action="signup_script.php"
                        method="POST"
                        class="signup-form">


                        <!-- NAME -->

                        <div class="form-group">

                            <label>
                                Full Name
                            </label>


                            <div class="input-box">

                                <i class="fa fa-user"></i>


                                <input
                                    type="text"
                                    class="signup-input"
                                    placeholder="Enter your name"
                                    name="name"
                                    maxlength="100"
                                    required>

                            </div>

                        </div>


                        <!-- EMAIL -->

                        <div class="form-group">

                            <label>
                                Email Address
                            </label>


                            <div class="input-box">

                                <i class="fa fa-envelope"></i>


                                <input
                                    type="email"
                                    class="signup-input"
                                    placeholder="Enter your email"
                                    name="email"
                                    maxlength="100"
                                    required>

                            </div>


                            <?php

                            if (isset($_GET["m1"])) {

                                echo '

                                <div class="field-error">'

                                .

                                htmlspecialchars(
                                    $_GET["m1"],
                                    ENT_QUOTES,
                                    'UTF-8'
                                )

                                .

                                '</div>';

                            }

                            ?>

                        </div>


                        <!-- PASSWORD -->

                        <div class="form-group">

                            <label>
                                Password
                            </label>


                            <div class="input-box">

                                <i class="fa fa-lock"></i>


                                <input
                                    type="password"
                                    class="signup-input"
                                    placeholder="Minimum 6 characters"
                                    name="password"
                                    minlength="6"
                                    required>

                            </div>

                        </div>


                        <!-- CONTACT + CITY -->

                        <div class="form-row">


                            <div class="form-half">

                                <div class="form-group">

                                    <label>
                                        Contact Number
                                    </label>


                                    <div class="input-box">

                                        <i class="fa fa-phone"></i>


                                        <input
                                            type="tel"
                                            class="signup-input"
                                            placeholder="03001234567"
                                            name="contact"
                                            maxlength="11"
                                            minlength="11"
                                            pattern="03[0-9]{9}"
                                            inputmode="numeric"
                                            required>

                                    </div>


                                    <?php

                                    if (isset($_GET["m2"])) {

                                        echo '

                                        <div class="field-error">'

                                        .

                                        htmlspecialchars(
                                            $_GET["m2"],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        )

                                        .

                                        '</div>';

                                    }

                                    ?>

                                </div>

                            </div>


                            <div class="form-half">

                                <div class="form-group">

                                    <label>
                                        City
                                    </label>


                                    <div class="input-box">

                                        <i class="fa fa-map-marker"></i>


                                        <input
                                            type="text"
                                            class="signup-input"
                                            placeholder="Your city"
                                            name="city"
                                            maxlength="100"
                                            required>

                                    </div>

                                </div>

                            </div>


                        </div>


                        <!-- ADDRESS -->

                        <div class="form-group">

                            <label>
                                Address
                            </label>


                            <div class="input-box">

                                <i class="fa fa-home"></i>


                                <input
                                    type="text"
                                    class="signup-input"
                                    placeholder="Enter your complete address"
                                    name="address"
                                    maxlength="255"
                                    required>

                            </div>

                        </div>


                        <!-- BUTTONS -->

                        <div class="signup-actions">


                            <button
                                type="submit"
                                class="create-btn">

                                <i class="fa fa-user-plus"></i>

                                &nbsp;

                                Create Account

                            </button>


                            <a
                                href="login.php"
                                class="login-btn">

                                <i class="fa fa-sign-in"></i>

                                &nbsp; Login

                            </a>


                        </div>


                    </form>


                    <div class="signup-security">

                        <i class="fa fa-lock"></i>

                        Your information is securely protected.

                    </div>


                </div>

            </div>


        </div>

    </div>

</div>


</body>

</html>