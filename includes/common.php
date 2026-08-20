<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "ecommerce",
    3306
);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8mb4");

?>