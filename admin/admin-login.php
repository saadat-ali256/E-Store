<?php

require_once "../includes/common.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Already logged in as admin
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header("Location: index.php");
    exit();
}

$error = "";

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | CHANGE THESE TWO VALUES
    |--------------------------------------------------------------------------
    */

    $admin_username = "admin";
    $admin_password = "Admin@12345";

    if (
        $username === $admin_username &&
        $password === $admin_password
    ) {

        $_SESSION['is_admin'] = true;
        $_SESSION['admin_username'] = $admin_username;

        header("Location: index.php");
        exit();

    } else {

        $error = "Invalid admin username or password.";

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

    width: 100%;

    max-width: 430px;

    background: white;

    border-radius: 20px;

    padding: 40px;

    box-shadow:
        0 20px 60px
        rgba(0,0,0,.25);
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

    color: #6b7280;

    font-size: 14px;

    margin-bottom: 30px;
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

.form-group {

    margin-bottom: 20px;
}

.form-group label {

    font-weight: 600;

    font-size: 13px;

    margin-bottom: 8px;
}

.form-control {

    height: 48px;

    border-radius: 10px;

    border: 1px solid #d1d5db;

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

    border-radius: 10px;

    background: #2563eb;

    color: white;

    font-weight: 600;

    font-size: 15px;

    margin-top: 5px;
}

.login-btn:hover {

    background: #1d4ed8;
}

.security {

    text-align: center;

    color: #6b7280;

    font-size: 12px;

    margin-top: 22px;
}

.security i {

    color: #16a34a;

    margin-right: 5px;
}

.error {

    background: #fef2f2;

    color: #dc2626;

    border: 1px solid #fecaca;

    border-radius: 9px;

    padding: 12px;

    margin-bottom: 20px;

    font-size: 13px;

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


    <?php if ($error != ""): ?>

        <div class="error">

            <i class="fa fa-exclamation-circle"></i>

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>


    <form method="POST">


        <div class="form-group">

            <label>

                Admin Username

            </label>

            <input
                type="text"
                name="username"
                class="form-control"
                placeholder="Enter admin username"
                required
                autocomplete="username"
            >

        </div>


        <div class="form-group">

            <label>

                Admin Password

            </label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Enter admin password"
                required
                autocomplete="current-password"
            >

        </div>


        <button
            type="submit"
            class="login-btn">

            <i class="fa fa-sign-in"></i>

            &nbsp;

            Login to Admin Panel

        </button>


    </form>


    <div class="security">

        <i class="fa fa-shield"></i>

        Protected Administrator Area

    </div>


</div>


</body>

</html>