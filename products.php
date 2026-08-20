<?php

include "includes/common.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];


/* =====================================================
   GET PRODUCTS
===================================================== */

$query = "
    SELECT id, name, description, price, image
    FROM products
    ORDER BY id DESC
";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Products error: " . mysqli_error($con));
}

$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}


/* =====================================================
   CATEGORY
===================================================== */

function get_category($name)
{
    $name = strtolower($name);

    if (
        strpos($name, 'iphone') !== false ||
        strpos($name, 'phone') !== false ||
        strpos($name, 'mobile') !== false ||
        strpos($name, 'samsung') !== false ||
        strpos($name, 'pixel') !== false
    ) {
        return 'iphone';
    }

    if (
        strpos($name, 'macbook') !== false ||
        strpos($name, 'laptop') !== false ||
        strpos($name, 'computer') !== false ||
        strpos($name, 'mac') !== false
    ) {
        return 'mac';
    }

    if (
        strpos($name, 'ipad') !== false ||
        strpos($name, 'tablet') !== false
    ) {
        return 'ipad';
    }

    if (
        strpos($name, 'watch') !== false ||
        strpos($name, 'band') !== false
    ) {
        return 'watch';
    }

    if (
        strpos($name, 'airpods') !== false ||
        strpos($name, 'airpod') !== false ||
        strpos($name, 'headphone') !== false ||
        strpos($name, 'earphone') !== false
    ) {
        return 'airpods';
    }

    return 'accessories';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>E-Store | Products</title>

<!-- TAB ICON / FAVICON -->
<link rel="icon"
      type="image/png"
      href="img/logo.png">

<link rel="shortcut icon"
      type="image/png"
      href="img/logo.png">

<link rel="stylesheet"
      href="bootstrap/css/bootstrap.min.css">

<link rel="stylesheet"
      href="css/font-awesome/css/font-awesome.min.css">

<script src="bootstrap/js/jquery-3.5.1.min.js"></script>

<script src="bootstrap/js/bootstrap.min.js"></script>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    padding-top: 52px;

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;

    color: #1d1d1f;
    background: #fff;
}


/* =====================================================
   NAVBAR
===================================================== */

.store-nav {
    position: fixed;

    top: 0;
    left: 0;

    width: 100%;
    height: 52px;

    background:
        rgba(250,250,252,.94);

    backdrop-filter: blur(20px);

    -webkit-backdrop-filter: blur(20px);

    border-bottom:
        1px solid rgba(0,0,0,.08);

    z-index: 9999;
}


.nav-inner {

    max-width: 1100px;

    height: 52px;

    margin: auto;

    padding:
        0 20px;

    display: flex;

    align-items: center;

    justify-content: space-between;
}


.brand {

    color: #111;

    font-size: 19px;

    font-weight: 700;

    text-decoration: none;

    letter-spacing: -.5px;
}


.brand:hover {

    color: #111;

    text-decoration: none;
}


.nav-links {

    display: flex;

    align-items: center;

    gap: 30px;
}


.nav-links a {

    color: #222;

    font-size: 12px;

    text-decoration: none;

    transition: .2s;
}


.nav-links a:hover {

    color: #777;
}


.cart-count {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 17px;

    height: 17px;

    padding: 0 5px;

    background: #0071e3;

    color: #fff;

    border-radius: 20px;

    font-size: 9px;

    margin-left: 2px;
}


/* =====================================================
   HERO
===================================================== */

.hero {

    min-height: 650px;

    background: #f5f5f7;

    text-align: center;

    padding:
        80px 20px 0;

    overflow: hidden;
}


.hero-small {

    color: #6e6e73;

    font-size: 14px;

    font-weight: 600;

    margin-bottom: 12px;
}


.hero h1 {

    max-width: 900px;

    margin: auto;

    font-size: 64px;

    line-height: 1;

    letter-spacing: -3px;

    font-weight: 700;
}


.hero-description {

    max-width: 650px;

    margin:
        20px auto 0;

    font-size: 22px;

    line-height: 1.4;

    color: #424245;
}


.hero-price {

    color: #6e6e73;

    font-size: 15px;

    margin-top: 12px;
}


.hero-buttons {

    margin-top: 24px;
}


.blue-button {

    display: inline-block;

    padding:
        11px 23px;

    border-radius: 25px;

    background: #0071e3;

    color: white;

    text-decoration: none;

    font-size: 14px;

    margin: 0 4px;

    transition: .2s;
}


.blue-button:hover {

    background: #0077ed;

    color: white;

    text-decoration: none;

    transform: translateY(-1px);
}


.learn-button {

    display: inline-block;

    padding:
        11px 12px;

    color: #0071e3;

    font-size: 14px;

    text-decoration: none;
}


.learn-button:hover {

    color: #0077ed;

    text-decoration: none;
}


.hero-image {

    width: 600px;

    height: 370px;

    max-width: 90%;

    object-fit: contain;

    margin-top: 40px;

    filter:
        drop-shadow(
            0 25px 30px
            rgba(0,0,0,.15)
        );
}


/* =====================================================
   SEARCH
===================================================== */

.search-section {

    background: #fff;

    padding:
        55px 20px 35px;

    text-align: center;
}


.search-section h2 {

    font-size: 30px;

    font-weight: 700;

    letter-spacing: -1px;

    margin:
        0 0 22px;
}


.search-box {

    max-width: 650px;

    margin: auto;

    position: relative;
}


.search-box i {

    position: absolute;

    left: 19px;

    top: 17px;

    color: #777;
}


.search-box input {

    width: 100%;

    height: 52px;

    padding:
        0 110px
        0 48px;

    border:
        1px solid #d2d2d7;

    border-radius: 28px;

    background: #f5f5f7;

    font-size: 15px;

    outline: none;
}


.search-box input:focus {

    background: #fff;

    border-color: #0071e3;

    box-shadow:
        0 0 0 3px
        rgba(0,113,227,.10);
}


.search-button {

    position: absolute;

    right: 6px;

    top: 6px;

    height: 40px;

    padding:
        0 20px;

    border: none;

    border-radius: 22px;

    background: #0071e3;

    color: #fff;

    font-weight: 600;
}


/* =====================================================
   CATEGORIES
===================================================== */

.categories {

    text-align: center;

    padding:
        10px 20px 45px;
}


.category-button {

    border: none;

    background: #f5f5f7;

    padding:
        9px 18px;

    border-radius: 22px;

    margin: 4px;

    font-size: 13px;

    transition: .2s;

    cursor: pointer;
}


.category-button:hover {

    background: #e5e5e7;
}


.category-button.active {

    background: #1d1d1f;

    color: white;
}


/* =====================================================
   FEATURED PRODUCT
===================================================== */

.featured {

    min-height: 620px;

    text-align: center;

    padding:
        75px 20px 0;

    margin-bottom: 12px;

    overflow: hidden;
}


.featured.light {

    background: #f5f5f7;
}


.featured.dark {

    background: #000;

    color: #fff;
}


.featured h2 {

    max-width: 850px;

    margin: auto;

    font-size: 50px;

    line-height: 1;

    letter-spacing: -2.5px;

    font-weight: 700;
}


.featured h3 {

    max-width: 650px;

    margin:
        17px auto;

    font-size: 21px;

    font-weight: 400;
}


.featured.dark h3 {

    color: #d2d2d7;
}


.featured-image {

    width: 570px;

    height: 350px;

    max-width: 90%;

    object-fit: contain;

    margin-top: 30px;

    transition: .4s;

    filter:
        drop-shadow(
            0 25px 30px
            rgba(0,0,0,.15)
        );
}


.featured:hover .featured-image {

    transform:
        scale(1.025)
        translateY(-5px);
}


/* =====================================================
   PRODUCTS
===================================================== */

.products-section {

    background: #f5f5f7;

    padding:
        80px 20px;
}


.products-heading {

    text-align: center;

    margin-bottom: 45px;
}


.products-heading h2 {

    font-size: 45px;

    letter-spacing: -2px;

    font-weight: 700;

    margin: 0;
}


.products-heading p {

    color: #6e6e73;

    margin-top: 10px;
}


/* =====================================================
   CARD
===================================================== */

.product-item {

    margin-bottom: 25px;
}


.product-card {

    background: #fff;

    border:
        1px solid #e8e8ed;

    border-radius: 20px;

    overflow: hidden;

    transition:
        transform .3s,
        box-shadow .3s;
}


.product-card:hover {

    transform:
        translateY(-6px);

    box-shadow:
        0 18px 40px
        rgba(0,0,0,.10);
}


.product-image-box {

    height: 270px;

    background: #fafafa;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;
}


.product-image-box img {

    width: 100%;

    height: 100%;

    object-fit: contain;

    transition: .35s;
}


.product-card:hover
.product-image-box img {

    transform: scale(1.06);
}


.product-info {

    padding: 23px;
}


.product-info h3 {

    font-size: 19px;

    line-height: 1.2;

    height: 45px;

    overflow: hidden;

    margin:
        0 0 9px;

    font-weight: 700;
}


.product-info p {

    color: #6e6e73;

    font-size: 13px;

    line-height: 1.4;

    height: 40px;

    overflow: hidden;

    margin: 0;
}


.price {

    font-size: 19px;

    font-weight: 700;

    margin:
        15px 0;
}


.add-button {

    width: 100%;

    border: none;

    border-radius: 22px;

    padding:
        11px;

    background: #0071e3;

    color: white;

    font-weight: 600;

    transition: .2s;
}


.add-button:hover {

    background: #0077ed;
}


.view-details {

    display: block;

    text-align: center;

    text-decoration: none !important;
}


.view-details:hover {

    color: #fff;

    text-decoration: none !important;
}


/* =====================================================
   SEE MORE
===================================================== */

.see-more-wrapper {

    text-align: center;

    margin-top: 30px;

    margin-bottom: 10px;
}


.see-more-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 9px;

    min-width: 155px;

    padding:
        12px 28px;

    border: none;

    border-radius: 25px;

    background: #0071e3;

    color: #fff;

    font-size: 14px;

    font-weight: 600;

    cursor: pointer;

    transition:
        all .25s ease;
}


.see-more-button:hover {

    background: #0077ed;

    color: #fff;

    transform:
        translateY(-2px);

    box-shadow:
        0 8px 20px
        rgba(0,113,227,.20);
}


.see-more-button i {

    font-size: 13px;
}


.see-more-button.hide-button {

    background: #1d1d1f;
}


.see-more-button.hide-button:hover {

    background: #333;
}


/* =====================================================
   NO RESULT
===================================================== */

.no-result {

    display: none;

    text-align: center;

    padding:
        70px 20px;
}


.no-result i {

    font-size: 45px;

    color: #999;
}


.no-result h3 {

    font-size: 25px;
}


/* =====================================================
   SERVICES
===================================================== */

.services {

    background: #fff;

    padding:
        65px 20px;
}


.service {

    text-align: center;

    padding: 20px;
}


.service i {

    font-size: 28px;

    margin-bottom: 15px;
}


.service h4 {

    font-weight: 700;
}


.service p {

    color: #6e6e73;

    font-size: 13px;
}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    background: #f5f5f7;

    padding:
        55px 20px 25px;

    color: #6e6e73;
}


.footer-brand {

    color: #1d1d1f;

    font-size: 20px;

    font-weight: 700;
}


.footer-title {

    color: #1d1d1f;

    font-size: 13px;

    font-weight: 700;

    margin-bottom: 12px;
}


.footer ul {

    list-style: none;

    padding: 0;
}


.footer li {

    margin-bottom: 8px;
}


.footer a {

    color: #6e6e73;

    font-size: 12px;

    text-decoration: none;
}


.footer-bottom {

    border-top:
        1px solid #d2d2d7;

    margin-top: 30px;

    padding-top: 20px;

    font-size: 11px;
}


/* =====================================================
   TOAST
===================================================== */

.toast {

    display: none;

    position: fixed;

    right: 20px;

    bottom: 20px;

    z-index: 99999;

    background: #1d1d1f;

    color: white;

    padding:
        14px 20px;

    border-radius: 12px;

    box-shadow:
        0 10px 35px
        rgba(0,0,0,.25);
}


.toast i {

    color: #4cd964;

    margin-right: 7px;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:767px) {

    .nav-inner {

        padding:
            0 15px;
    }


    .nav-links {

        gap: 18px;
    }


    .nav-links a:not(:last-child) {

        display: none;
    }


    .hero {

        min-height: 560px;

        padding-top: 65px;
    }


    .hero h1 {

        font-size: 42px;

        letter-spacing: -2px;
    }


    .hero-description {

        font-size: 18px;
    }


    .hero-image {

        width: 420px;

        height: 270px;
    }


    .featured {

        min-height: 550px;

        padding-top: 60px;
    }


    .featured h2 {

        font-size: 38px;
    }


    .featured h3 {

        font-size: 18px;
    }


    .featured-image {

        width: 420px;

        height: 270px;
    }


    .products-heading h2 {

        font-size: 36px;
    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:420px) {

    .hero h1 {

        font-size: 36px;
    }


    .featured h2 {

        font-size: 33px;
    }


    .category-button {

        padding:
            8px 13px;

        font-size: 12px;
    }


    .see-more-button {

        min-width: 140px;

        padding:
            11px 22px;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="store-nav">

<div class="nav-inner">

<a href="products.php"
   class="brand">

E-Store

</a>


<div class="nav-links">

<a href="products.php">
Store
</a>

<a href="#products">
Products
</a>

<a href="#categories">
Categories
</a>

<a href="#services">
Support
</a>

<a href="#search">
<i class="fa fa-search"></i>
</a>

<a href="cart.php">

<i class="fa fa-shopping-bag"></i>

<span
    class="cart-count"
    id="cartCount">

0

</span>

</a>

</div>

</div>

</nav>


<!-- =====================================================
     HERO
===================================================== -->

<?php if (!empty($products)):

$hero = $products[0];

?>

<section class="hero">

<div class="hero-small">
NEW
</div>


<h1>

<?php
echo htmlspecialchars($hero['name']);
?>

</h1>


<div class="hero-description">

<?php
echo htmlspecialchars($hero['description']);
?>

</div>


<div class="hero-price">

Starting from Rs.

<?php
echo number_format($hero['price'], 2);
?>

</div>


<div class="hero-buttons">

<a href="#products"
   class="blue-button">

Buy

</a>


<a href="#products"
   class="learn-button">

Learn more
<i class="fa fa-angle-right"></i>

</a>

</div>


<img
    src="img/<?php
        echo htmlspecialchars($hero['image']);
    ?>"
    class="hero-image"
    alt="<?php
        echo htmlspecialchars($hero['name']);
    ?>">


</section>

<?php else: ?>

<section class="hero">

<h1>
E-Store
</h1>

<div class="hero-description">
Discover amazing products.
</div>

</section>

<?php endif; ?>


<!-- =====================================================
     SEARCH
===================================================== -->

<section
    class="search-section"
    id="search">

<h2>
What are you looking for?
</h2>


<div class="search-box">

<i class="fa fa-search"></i>

<input
    type="text"
    id="searchInput"
    placeholder="Search products...">

<button
    type="button"
    class="search-button"
    id="searchButton">

Search

</button>

</div>

</section>


<!-- =====================================================
     CATEGORIES
===================================================== -->

<section
    class="categories"
    id="categories">

<button
    class="category-button active"
    data-category="all">

All

</button>

<button
    class="category-button"
    data-category="iphone">

iPhone

</button>

<button
    class="category-button"
    data-category="mac">

Mac

</button>

<button
    class="category-button"
    data-category="ipad">

iPad

</button>

<button
    class="category-button"
    data-category="watch">

Watch

</button>

<button
    class="category-button"
    data-category="airpods">

AirPods

</button>

<button
    class="category-button"
    data-category="accessories">

Accessories

</button>

</section>


<!-- =====================================================
     FEATURED PRODUCTS
===================================================== -->

<?php

$featuredProducts =
    array_slice($products, 1, 3);

$colors = [
    'light',
    'dark',
    'light'
];

$index = 0;

foreach ($featuredProducts as $product):

?>

<section
    class="featured <?php
        echo $colors[$index % 3];
    ?>">

<h2>

<?php
echo htmlspecialchars($product['name']);
?>

</h2>


<h3>

<?php
echo htmlspecialchars($product['description']);
?>

</h3>


<div>

<a
    href="product-details.php?id=<?php echo (int)$product['id']; ?>"
    class="blue-button">

Buy

</a>


<a
    href="product-details.php?id=<?php echo (int)$product['id']; ?>"
    class="learn-button">

Learn more
<i class="fa fa-angle-right"></i>

</a>

</div>


<img
    src="img/<?php
        echo htmlspecialchars($product['image']);
    ?>"
    class="featured-image"
    alt="<?php
        echo htmlspecialchars($product['name']);
    ?>">

</section>


<?php

$index++;

endforeach;

?>


<!-- =====================================================
     ALL PRODUCTS
===================================================== -->

<section
    class="products-section"
    id="products">


<div class="products-heading">

<h2>
Explore all products.
</h2>

<p>
Find something perfect for you.
</p>

</div>


<div class="container">

<div class="row"
     id="productGrid">


<?php

$productIndex = 0;

foreach ($products as $product):

$category =
    get_category(
        $product['name']
    );

?>


<div
    class="col-sm-6 col-md-4 product-item"

    data-product-index="<?php
        echo $productIndex;
    ?>"

    data-name="<?php
        echo htmlspecialchars(
            strtolower(
                $product['name']
            )
        );
    ?>"

    data-category="<?php
        echo $category;
    ?>"
>


<div class="product-card">


<div class="product-image-box">

<img
    src="img/<?php
        echo htmlspecialchars(
            $product['image']
        );
    ?>"
    alt="<?php
        echo htmlspecialchars(
            $product['name']
        );
    ?>">

</div>


<div class="product-info">


<h3>

<?php
echo htmlspecialchars(
    $product['name']
);
?>

</h3>


<p>

<?php
echo htmlspecialchars(
    $product['description']
);
?>

</p>


<div class="price">

Rs.

<?php
echo number_format(
    $product['price'],
    2
);
?>

</div>


<a
    href="product-details.php?id=<?php echo (int)$product['id']; ?>"
    class="add-button view-details">

<i class="fa fa-eye"></i>

View Details

</a>


</div>

</div>

</div>


<?php

$productIndex++;

endforeach;

?>


</div>


<!-- =====================================================
     SEE MORE BUTTON
===================================================== -->

<?php if (count($products) > 6): ?>

<div
    class="see-more-wrapper"
    id="seeMoreWrapper">

<button
    type="button"
    class="see-more-button"
    id="seeMoreButton">

<span id="seeMoreText">
See More
</span>

<i
    class="fa fa-angle-down"
    id="seeMoreIcon">
</i>

</button>

</div>

<?php endif; ?>


<!-- =====================================================
     NO RESULT
===================================================== -->

<div
    class="no-result"
    id="noResult">

<i class="fa fa-search"></i>

<h3>
No products found.
</h3>

<p>
Try another search.
</p>

</div>


</div>

</section>


<!-- =====================================================
     SERVICES
===================================================== -->

<section
    class="services"
    id="services">

<div class="container">

<div class="row">


<div class="col-sm-4">

<div class="service">

<i class="fa fa-truck"></i>

<h4>
Fast Delivery
</h4>

<p>
Fast and reliable delivery
for your orders.
</p>

</div>

</div>


<div class="col-sm-4">

<div class="service">

<i class="fa fa-shield"></i>

<h4>
Secure Shopping
</h4>

<p>
A simple and secure
shopping experience.
</p>

</div>

</div>


<div class="col-sm-4">

<div class="service">

<i class="fa fa-headphones"></i>

<h4>
Customer Support
</h4>

<p>
We're here when you
need assistance.
</p>

</div>

</div>


</div>

</div>

</section>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">

<div class="container">

<div class="row">


<div class="col-sm-4">

<div class="footer-brand">
E-Store
</div>

<p>
Premium products for
everyday life.
</p>

</div>


<div class="col-sm-2">

<div class="footer-title">
Shop
</div>

<ul>

<li>
<a href="#products">
Products
</a>
</li>

<li>
<a href="#categories">
Categories
</a>
</li>

<li>
<a href="#search">
Search
</a>
</li>

</ul>

</div>


<div class="col-sm-2">

<div class="footer-title">
Account
</div>

<ul>

<li>
<a href="cart.php">
Shopping Bag
</a>
</li>

<li>
<a href="logout.php">
Logout
</a>
</li>

</ul>

</div>


<div class="col-sm-2">

<div class="footer-title">
Support
</div>

<ul>

<li>
<a href="#services">
Support
</a>
</li>

<li>
<a href="#">
Contact
</a>
</li>

</ul>

</div>


</div>


<div class="footer-bottom">

© <?php echo date('Y'); ?>
E-Store. All rights reserved.

</div>

</div>

</footer>


<!-- =====================================================
     TOAST
===================================================== -->

<div
    class="toast"
    id="toast">

<i class="fa fa-check-circle"></i>

<span id="toastText">
Added to cart
</span>

</div>


<script>


/* =====================================================
   PRODUCTS PER PAGE
===================================================== */

var productsToShow = 6;

var showingAll = false;


/* =====================================================
   FILTER PRODUCTS
===================================================== */

function filterProducts() {

    var search =
        $("#searchInput")
        .val()
        .toLowerCase()
        .trim();


    var category =
        $(".category-button.active")
        .data("category");


    var matchingProducts = [];


    $(".product-item").each(function() {

        var item = $(this);

        var name =
            String(
                item.data("name")
            );

        var itemCategory =
            item.data("category");


        var searchMatch =
            name.indexOf(search) !== -1;


        var categoryMatch =
            category === "all" ||
            category === itemCategory;


        if (
            searchMatch &&
            categoryMatch
        ) {

            matchingProducts.push(item);

        }

    });


    /*
       Search/category change par
       hamesha first 6 products show honge.
    */

    showingAll = false;


    $.each(
        matchingProducts,
        function(index, item) {

            if (
                index < productsToShow
            ) {

                $(item).show();

            } else {

                $(item).hide();

            }

        }
    );


    /*
       Agar matching products zero hain
    */

    if (
        matchingProducts.length === 0
    ) {

        $("#noResult").show();

    } else {

        $("#noResult").hide();

    }


    /*
       See More button
    */

    if (
        matchingProducts.length >
        productsToShow
    ) {

        $("#seeMoreWrapper").show();

        $("#seeMoreText")
            .text("See More");

        $("#seeMoreIcon")
            .removeClass("fa-angle-up")
            .addClass("fa-angle-down");

        $("#seeMoreButton")
            .removeClass("hide-button");

    } else {

        $("#seeMoreWrapper").hide();

    }

}


/* =====================================================
   SEE MORE
===================================================== */

$("#seeMoreButton").on(
    "click",
    function() {

        var button =
            $(this);


        if (!showingAll) {

            /*
               SHOW ALL
            */

            $(".product-item").each(function() {

                var item =
                    $(this);

                var search =
                    $("#searchInput")
                    .val()
                    .toLowerCase()
                    .trim();

                var category =
                    $(".category-button.active")
                    .data("category");

                var name =
                    String(
                        item.data("name")
                    );

                var itemCategory =
                    item.data("category");


                var searchMatch =
                    name.indexOf(search) !== -1;


                var categoryMatch =
                    category === "all" ||
                    category === itemCategory;


                if (
                    searchMatch &&
                    categoryMatch
                ) {

                    item.show();

                } else {

                    item.hide();

                }

            });


            showingAll = true;


            $("#seeMoreText")
                .text("Show Less");


            $("#seeMoreIcon")
                .removeClass(
                    "fa-angle-down"
                )
                .addClass(
                    "fa-angle-up"
                );


            button
                .addClass(
                    "hide-button"
                );


        } else {

            /*
               SHOW ONLY FIRST 6
            */

            var count = 0;


            $(".product-item").each(function() {

                var item =
                    $(this);

                var search =
                    $("#searchInput")
                    .val()
                    .toLowerCase()
                    .trim();

                var category =
                    $(".category-button.active")
                    .data("category");

                var name =
                    String(
                        item.data("name")
                    );

                var itemCategory =
                    item.data("category");


                var searchMatch =
                    name.indexOf(search) !== -1;


                var categoryMatch =
                    category === "all" ||
                    category === itemCategory;


                if (
                    searchMatch &&
                    categoryMatch
                ) {

                    if (
                        count <
                        productsToShow
                    ) {

                        item.show();

                    } else {

                        item.hide();

                    }

                    count++;

                } else {

                    item.hide();

                }

            });


            showingAll = false;


            $("#seeMoreText")
                .text("See More");


            $("#seeMoreIcon")
                .removeClass(
                    "fa-angle-up"
                )
                .addClass(
                    "fa-angle-down"
                );


            button
                .removeClass(
                    "hide-button"
                );


            /*
               Products section par
               smooth scroll
            */

            $("html, body").animate({

                scrollTop:
                    $("#products")
                    .offset()
                    .top - 60

            }, 400);

        }

    }
);


/* =====================================================
   LIVE SEARCH
===================================================== */

$("#searchInput").on(
    "keyup",
    function() {

        filterProducts();

    }
);


/* =====================================================
   SEARCH BUTTON
===================================================== */

$("#searchButton").on(
    "click",
    function() {

        filterProducts();


        $("html, body").animate({

            scrollTop:
                $("#products")
                .offset()
                .top - 60

        }, 500);

    }
);


/* =====================================================
   ENTER KEY
===================================================== */

$("#searchInput").on(
    "keypress",
    function(e) {

        if (
            e.which === 13
        ) {

            filterProducts();

        }

    }
);


/* =====================================================
   CATEGORY
===================================================== */

$(".category-button").on(
    "click",
    function() {

        $(".category-button")
            .removeClass("active");


        $(this)
            .addClass("active");


        filterProducts();

    }
);


/* =====================================================
   TOAST
===================================================== */

function showToast(message) {

    $("#toastText")
        .text(message);


    $("#toast")
        .stop(true, true)
        .fadeIn(200)
        .delay(1700)
        .fadeOut(400);

}


/* =====================================================
   CART COUNT
===================================================== */

function updateCartCount() {

    $.ajax({

        url:
            "cart-count.php",

        type:
            "GET",

        cache:
            false,

        success:
            function(response) {

                response =
                    $.trim(response);


                if (
                    $.isNumeric(response)
                ) {

                    $("#cartCount")
                        .text(response);

                }

            },

        error:
            function() {

                $("#cartCount")
                    .text("0");

            }

    });

}


/* =====================================================
   INITIAL LOAD
===================================================== */

$(document).ready(function() {

    filterProducts();

    updateCartCount();

});


</script>


</body>
</html>