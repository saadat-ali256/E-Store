<?php

// Start session agar already start nahi hai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| ADMIN LOGIN PROTECTION
|--------------------------------------------------------------------------
| Sirf logged-in admin ko admin panel ki pages access hongi.
| Agar koi normal user ya direct URL se access karega,
| to usay admin login page par bhej diya jayega.
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true
) {

    header("Location: index.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| ADMIN EMAIL CHECK
|--------------------------------------------------------------------------
| Admin login ke waqt email session mein save hoti hai.
| Agar session email missing ho to login page par wapas bhej dein.
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['admin_email']) ||
    empty($_SESSION['admin_email'])
) {

    // Admin session destroy
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_email']);

    header("Location: index.php");
    exit();
}