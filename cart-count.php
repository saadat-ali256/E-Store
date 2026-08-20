<?php

include "includes/common.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {

    echo "0";

    exit();
}


$user_id =
    (int)$_SESSION['user_id'];


$query = "
    SELECT COUNT(*) AS total
    FROM users_items
    WHERE user_id = $user_id
    AND status = 'Added to cart'
";


$result =
    mysqli_query(
        $con,
        $query
    );


if (!$result) {

    echo "0";

    exit();
}


$row =
    mysqli_fetch_assoc($result);


echo (int)$row['total'];

?>