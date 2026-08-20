<?php

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ======================================================
// DESTROY ADMIN SESSION
// ======================================================

unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_email']);


// ======================================================
// OPTIONAL: REGENERATE SESSION ID
// ======================================================

session_regenerate_id(true);


// ======================================================
// REDIRECT TO ADMIN LOGIN
// ======================================================

header("Location: index.php");
exit();

?>