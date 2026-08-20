<?php

require_once "../includes/common.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =========================
   ALREADY LOGGED IN
========================= */

if (
    isset($_SESSION['is_admin']) &&
    $_SESSION['is_admin'] === true
) {
    header("Location: index.php");
    exit();
}


$error = "";


/* =========================
   ADMIN LOGIN
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    /* =========================
       ADMIN CREDENTIALS
    ========================= */

    $admin_email = "admin@estore.com";
    $admin_password = "admin123";


    if (
        $email === $admin_email &&
        $password === $admin_password
    ) {

        /* Create admin session */

        $_SESSION['is_admin'] = true;
        $_SESSION['admin_email'] = $admin_email;


        /* Regenerate session ID */

        session_regenerate_id(true);


        /* Redirect */

        header("Location: index.php");
        exit();

    } else {

        $error = "Invalid admin email or password.";

    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>E-Store | Admin Login</title>

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

    min-height: 100vh;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #111827,
            #1e3a8a
        );

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;
}


.login-box {

    width: 420px;

    max-width: 92%;

    background: white;

    padding: 40px;

    border-radius: 22px;

    box-shadow:
        0 20px 60px
        rgba(0,0,0,.25);
}


.admin-icon {

    width: 70px;

    height: 70px;

    margin: 0 auto 20px;

    border-radius: 50%;

    background: #eff6ff;

    color: #2563eb;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 30px;
}


.logo {

    text-align: center;

    font-size: 30px;

    font-weight: 700;

    margin-bottom: 8px;

    color: #111827;
}


.logo span {

    color: #2563eb;
}


.subtitle {

    text-align: center;

    color: #6e6e73;

    margin-bottom: 30px;

    font-size: 14px;
}


.form-group {

    margin-bottom: 20px;
}


.form-group label {

    display: block;

    font-size: 13px;

    font-weight: 600;

    margin-bottom: 7px;
}


.form-control {

    width: 100%;

    height: 48px;

    border-radius: 12px;

    border: 1px solid #d2d2d7;

    padding: 0 14px;

    box-shadow: none;
}


.form-control:focus {

    border-color: #2563eb;

    box-shadow:
        0 0 0 3px
        rgba(37,99,235,.12);
}


.login-btn {

    width: 100%;

    height: 48px;

    border: none;

    border-radius: 24px;

    background: #2563eb;

    color: white;

    font-weight: 600;

    font-size: 15px;

    margin-top: 5px;

    cursor: pointer;
}


.login-btn:hover {

    background: #1d4ed8;
}


.error {

    background: #fef2f2;

    color: #dc2626;

    border: 1px solid #fecaca;

    padding: 12px;

    border-radius: 10px;

    margin-bottom: 20px;

    text-align: center;

    font-size: 13px;
}


.security {

    text-align: center;

    margin-top: 22px;

    color: #6b7280;

    font-size: 12px;
}


.security i {

    color: #16a34a;

    margin-right: 5px;
}


.back {

    display: block;

    text-align: center;

    margin-top: 20px;

    color: #2563eb;

    text-decoration: none;

    font-size: 14px;
}


.back:hover {

    color: #1d4ed8;

    text-decoration: none;
}

</style>

</head>


<body>


<div class="login-box">


    <div class="admin-icon">

        <i class="fa fa-lock"></i>

    </div>


    <div class="logo">

        E-<span>Store</span>

    </div>


    <div class="subtitle">

        Administrator Login

    </div>


    <?php if ($error !== ""): ?>

        <div class="error">

            <i class="fa fa-exclamation-circle"></i>

            <?php echo htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ); ?>

        </div>

    <?php endif; ?>


    <form method="POST">


        <div class="form-group">

            <label>
                Admin Email
            </label>

            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="admin@estore.com"
                required
                autocomplete="username"
            >

        </div>


        <div class="form-group">

            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Enter password"
                required
                autocomplete="current-password"
            >

        </div>


        <button
            type="submit"
            class="login-btn"
        >

            <i class="fa fa-sign-in"></i>

            &nbsp;

            Login to Admin Panel

        </button>


    </form>


    <div class="security">

        <i class="fa fa-shield"></i>

        Protected Administrator Area

    </div>


    <a
        href="../products.php"
        class="back"
    >

        <i class="fa fa-arrow-left"></i>

        Back to Store

    </a>


</div>


</body>

</html>