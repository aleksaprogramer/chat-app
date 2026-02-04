<?php

if (isset($_GET['router'])) {

    $router = $_GET['router'];

    switch ($router) {
        case 'register':
            require_once '././modules/register.php';
            break;
        
        case 'login':
            require_once '././modules/login.php';
            break;

        case 'homepage':
            require_once '././modules/homepage.php';
            break;

        case 'chat':
            require_once '././modules/chat.php';
            break;

        case 'admin-page':
            require_once '././modules/admin-page.php';
            break;

        default:
            exit("404. Page not found");
    }

} else {
    exit("404. Page not found");
}