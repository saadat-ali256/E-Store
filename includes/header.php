<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['email']);

?>

<style>

/* =====================================================
   MODERN E-STORE NAVBAR
===================================================== */

.store-navbar {
    background: rgba(255, 255, 255, 0.96);
    border: none;
    border-bottom: 1px solid #e8e8e8;
    border-radius: 0;
    margin-bottom: 0;
    min-height: 68px;

    box-shadow:
        0 4px 20px rgba(0, 0, 0, 0.06);

    position: relative;
    z-index: 9999;

    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}


/* =====================================================
   CONTAINER
===================================================== */

.store-navbar .container {
    min-height: 68px;
}


/* =====================================================
   BRAND
===================================================== */

.store-brand {
    font-size: 24px !important;
    font-weight: 800;

    color: #111 !important;

    letter-spacing: -0.8px;

    padding-top: 21px !important;
    padding-bottom: 21px !important;

    transition: all .25s ease;
}


.store-brand i {
    font-size: 24px;
    color: #111;

    margin-right: 7px;

    transition: transform .3s ease;
}


.store-brand:hover {
    color: #0071e3 !important;
}


.store-brand:hover i {
    color: #0071e3;
    transform: scale(1.08);
}


/* =====================================================
   NAV LINKS
===================================================== */

.store-navbar .navbar-nav > li > a {

    position: relative;

    color: #333;

    font-size: 13px;

    font-weight: 600;

    padding-top: 24px;
    padding-bottom: 22px;

    margin-left: 4px;

    transition:
        color .25s ease,
        background .25s ease;
}


.store-navbar .navbar-nav > li > a:hover {

    color: #0071e3;

    background: transparent;
}


/* =====================================================
   LINK UNDERLINE ANIMATION
===================================================== */

.store-navbar .navbar-nav > li:not(.nav-special) > a:after {

    content: "";

    position: absolute;

    left: 50%;
    bottom: 13px;

    width: 0;
    height: 2px;

    background: #0071e3;

    border-radius: 10px;

    transform: translateX(-50%);

    transition: width .25s ease;
}


.store-navbar .navbar-nav > li:not(.nav-special) > a:hover:after {

    width: 22px;
}


/* =====================================================
   LOGIN BUTTON
===================================================== */

.nav-login-btn {

    margin-top: 13px;

    margin-left: 8px !important;
    margin-right: 6px;

    padding: 9px 19px !important;

    border-radius: 24px;

    background: #0071e3 !important;

    color: #fff !important;

    border: 1px solid #0071e3;

    transition:
        all .25s ease !important;
}


.nav-login-btn:hover {

    background: #005bb5 !important;

    border-color: #005bb5;

    color: #fff !important;

    transform: translateY(-2px);

    box-shadow:
        0 5px 14px rgba(0, 113, 227, .25);
}


/* =====================================================
   SIGN UP BUTTON
===================================================== */

.nav-signup-btn {

    margin-top: 13px;

    margin-left: 3px !important;

    padding: 9px 18px !important;

    border-radius: 24px;

    border: 1px solid #0071e3;

    color: #0071e3 !important;

    background: #fff !important;

    transition:
        all .25s ease !important;
}


.nav-signup-btn:hover {

    background: #0071e3 !important;

    color: #fff !important;

    transform: translateY(-2px);

    box-shadow:
        0 5px 14px rgba(0, 113, 227, .20);
}


/* =====================================================
   ICONS
===================================================== */

.store-navbar .navbar-nav > li > a i {

    margin-right: 5px;

    transition:
        transform .25s ease;
}


.store-navbar .navbar-nav > li > a:hover i {

    transform: translateY(-1px);
}


/* =====================================================
   CART
===================================================== */

.cart-link {

    position: relative;

    padding-left: 18px !important;
    padding-right: 18px !important;
}


.cart-link i {

    font-size: 16px;

    margin-right: 5px !important;
}


/* =====================================================
   CART BADGE
===================================================== */

.cart-badge {

    position: absolute;

    top: 11px;
    right: 4px;

    min-width: 18px;
    height: 18px;

    line-height: 18px;

    text-align: center;

    border-radius: 50%;

    background: #ff3b30;

    color: white;

    font-size: 9px;

    font-weight: 700;

    border: 2px solid white;

    box-shadow:
        0 2px 6px rgba(0,0,0,.15);
}


/* =====================================================
   USER
===================================================== */

.user-link {

    max-width: 190px;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}


.user-link i {

    color: #0071e3;

    margin-right: 6px;
}


/* =====================================================
   LOGOUT
===================================================== */

.store-navbar .navbar-nav > li:last-child > a {

    color: #555;
}


.store-navbar .navbar-nav > li:last-child > a:hover {

    color: #e53935;

    background: transparent;
}


/* =====================================================
   MOBILE TOGGLE
===================================================== */

.store-navbar .navbar-toggle {

    margin-top: 15px;

    margin-right: 5px;

    border: 1px solid #ddd;

    border-radius: 8px;

    padding: 8px 9px;

    transition: all .25s ease;
}


.store-navbar .navbar-toggle:hover {

    background: #f5f5f5;

    border-color: #ccc;
}


.store-navbar .navbar-toggle .icon-bar {

    background: #222;

    width: 20px;

    height: 2px;

    border-radius: 5px;
}


/* =====================================================
   MOBILE MENU
===================================================== */

@media (max-width: 767px) {

    .store-navbar {

        min-height: 60px;

        background: rgba(255,255,255,.98);
    }


    .store-navbar .container {

        min-height: 60px;
    }


    .store-brand {

        font-size: 21px !important;

        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }


    .store-brand i {

        font-size: 21px;
    }


    .store-navbar .navbar-collapse {

        background: rgba(255,255,255,.99);

        border-top: 1px solid #eee;

        box-shadow:
            0 8px 20px rgba(0,0,0,.08);

        padding: 8px 0 15px;
    }


    .store-navbar .navbar-nav {

        margin-top: 0;

        margin-bottom: 0;
    }


    .store-navbar .navbar-nav > li > a {

        padding: 14px 20px;

        margin: 0;

        border-bottom: 1px solid #f1f1f1;

        font-size: 14px;
    }


    .store-navbar .navbar-nav > li > a:after {

        display: none;
    }


    /* Mobile Login */

    .nav-login-btn {

        margin: 10px 15px 5px !important;

        text-align: center;

        border-radius: 8px;

        padding: 11px !important;
    }


    /* Mobile Signup */

    .nav-signup-btn {

        margin: 5px 15px 10px !important;

        text-align: center;

        border-radius: 8px;

        padding: 11px !important;
    }


    /* Mobile Cart */

    .cart-link {

        padding-left: 20px !important;
    }


    .cart-badge {

        top: 10px;

        left: 58px;

        right: auto;
    }


    /* Mobile User */

    .user-link {

        max-width: none;
    }

}

</style>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar navbar-default store-navbar">

    <div class="container">


        <!-- =================================================
             MOBILE TOGGLE
        ================================================= -->

        <div class="navbar-header">

            <button
                type="button"
                class="navbar-toggle collapsed"
                data-toggle="collapse"
                data-target="#estoreNavbar"
                aria-expanded="false">

                <span class="sr-only">
                    Toggle navigation
                </span>

                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>

            </button>


            <!-- =================================================
                 BRAND
            ================================================= -->

            <a
                class="navbar-brand store-brand"
                href="<?php echo $is_logged_in ? 'products.php' : 'index.php'; ?>">

                <i class="fa fa-apple"></i>

                E-Store

            </a>

        </div>


        <!-- =================================================
             NAVIGATION
        ================================================= -->

        <div
            class="collapse navbar-collapse"
            id="estoreNavbar">


            <ul class="nav navbar-nav navbar-right">


                <!-- HOME -->

                <li>

                    <a href="index.php">

                        <i class="fa fa-home"></i>

                        Home

                    </a>

                </li>


                <?php if ($is_logged_in) { ?>


                    <!-- PRODUCTS -->

                    <li>

                        <a href="products.php">

                            <i class="fa fa-mobile"></i>

                            Products

                        </a>

                    </li>


                    <!-- CART -->

                    <li>

                        <a
                            href="cart.php"
                            class="cart-link">

                            <i class="fa fa-shopping-cart"></i>

                            Cart

                            <?php
                            /*
                             * Agar tum baad mein cart count
                             * database se lana chaho to
                             * yahan cart-badge add kar sakte ho.
                             */
                            ?>

                        </a>

                    </li>


                    <!-- USER -->

                    <li>

                        <a
                            href="settings.php"
                            class="user-link">

                            <i class="fa fa-user-circle"></i>

                            <?php

                            echo htmlspecialchars(
                                $_SESSION['email'],
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </a>

                    </li>


                    <!-- LOGOUT -->

                    <li>

                        <a href="logout.php">

                            <i class="fa fa-sign-out"></i>

                            Logout

                        </a>

                    </li>


                <?php } else { ?>


                    <!-- LOGIN -->

                    <li class="nav-special">

                        <a
                            href="login.php"
                            class="nav-login-btn">

                            <i class="fa fa-sign-in"></i>

                            Login

                        </a>

                    </li>


                    <!-- SIGN UP -->

                    <li class="nav-special">

                        <a
                            href="signup.php"
                            class="nav-signup-btn">

                            <i class="fa fa-user-plus"></i>

                            Sign Up

                        </a>

                    </li>


                <?php } ?>


            </ul>

        </div>

    </div>

</nav>