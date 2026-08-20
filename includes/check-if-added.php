<?php

function check_if_added_to_cart($item_id) {

    global $con;

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $user_id = (int) $_SESSION['user_id'];
    $item_id = (int) $item_id;

    $query = "SELECT id
              FROM users_items
              WHERE user_id = ?
              AND item_id = ?
              AND status = 'Added to cart'
              LIMIT 1";

    $stmt = mysqli_prepare($con, $query);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ii", $user_id, $item_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_num_rows($result) > 0;
}

?>