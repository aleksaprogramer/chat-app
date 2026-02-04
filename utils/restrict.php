<?php

function restrict ($environment, $database) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT is_admin FROM users WHERE id = $user_id";
    $run = $database->query($sql);
    $results = $run->fetch_assoc();

    $user = $results;

    if ($user['is_admin'] === '0') {
        header("Location: $environment->base_url" . "/?router=homepage");
        exit();
    }
}