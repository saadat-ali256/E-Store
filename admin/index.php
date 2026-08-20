<?php

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

// Agar admin already login hai
// to dobara login page par na aaye
if (
    isset($_SESSION["admin_logged_in"]) &&
    $_SESSION["admin_logged_in"] === true
) {
    header("Location: dashboard.php");
    exit();
}


// Login form submit hua
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";


    // Admin credentials
    if (
        $email === "admin@estore.com" &&
        $password === "admin123"
    ) {

        // Admin session
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_email"] = $email;

        // Dashboard par redirect
        header("Location: dashboard.php");
        exit();

    } else {

        $error = "Wrong email or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>E-Store Admin Login</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f5f7;
            font-family: Arial, sans-serif;
        }

        .login {
            width: 350px;
            max-width: 90%;
            margin: 100px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,.15);
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #0071e3;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #0071e3;
            color: white;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        button:hover {
            background: #005bb5;
        }

        .error {
            background: #ffe5e5;
            color: #d00000;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 7px;
        }

    </style>

</head>

<body>

<div class="login">

    <h1>E-Store Admin</h1>

    <p class="subtitle">
        Admin Login
    </p>

    <?php if ($error !== ""): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>


    <form method="POST" action="">

        <label for="email">
            Email
        </label>

        <input
            type="email"
            id="email"
            name="email"
            placeholder="admin@estore.com"
            autocomplete="username"
            required
        >


        <label for="password">
            Password
        </label>

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter password"
            autocomplete="current-password"
            required
        >


        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>

</html>