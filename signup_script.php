<?php

require("includes/common.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: signup.php");
    exit();
}


/* Get form data */

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$contact = trim($_POST['contact'] ?? '');
$city = trim($_POST['city'] ?? '');
$address = trim($_POST['address'] ?? '');


/* Validate required fields */

if ($name === '' || $email === '' || $password === '' ||
    $contact === '' || $city === '' || $address === '') {

    header("Location: signup.php?m1=" . urlencode("All fields are required"));
    exit();
}


/* Clean data */

$name = mysqli_real_escape_string($con, $name);
$email = mysqli_real_escape_string($con, $email);
$password = mysqli_real_escape_string($con, $password);
$contact = mysqli_real_escape_string($con, $contact);
$city = mysqli_real_escape_string($con, $city);
$address = mysqli_real_escape_string($con, $address);


/* Email validation */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    header("Location: signup.php?m1=" . urlencode("Not a valid email address"));
    exit();
}


/* Password validation */

if (strlen($password) < 6) {

    header("Location: signup.php?m1=" . urlencode("Password must be at least 6 characters"));
    exit();
}


/* Pakistani mobile number validation */
/* Example: 03001234567 */

if (!preg_match('/^03[0-9]{9}$/', $contact)) {

    header("Location: signup.php?m2=" . urlencode(
        "Enter a valid 11 digit number e.g. 03001234567"
    ));

    exit();
}


/* Check email already exists */

$query = "SELECT id FROM users WHERE email='$email' LIMIT 1";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Database error: " . mysqli_error($con));
}


if (mysqli_num_rows($result) > 0) {

    header("Location: signup.php?m1=" . urlencode(
        "Email already exists"
    ));

    exit();
}


/* Insert new user */

$query = "INSERT INTO users
          (name, email, password, contact, city, address)
          VALUES
          ('$name', '$email', '$password', '$contact', '$city', '$address')";


if (!mysqli_query($con, $query)) {

    die("Signup failed: " . mysqli_error($con));
}


/* Get new user ID */

$user_id = mysqli_insert_id($con);


/* Create login session */

$_SESSION['email'] = $email;
$_SESSION['user_id'] = $user_id;


/* Go to products */

header("Location: products.php");
exit();

?>