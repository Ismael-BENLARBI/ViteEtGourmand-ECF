<?php
session_start();

$page = $_GET['page'] ?? 'home';

switch($page) {
    case 'home':
        require_once 'Views/home/index.php';
        break;

    case 'menus':
        require_once 'views/menus/index.php';
        break;

    case 'contact':
        require_once 'views/contact/index.php';
        break;

    case 'login':
        require_once 'views/auth/login.php'; 
        break;

    case 'profil':
        require_once 'views/users/profil.php'; 
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?page=home');
        exit;
        break;

    default:
        http_response_code(404);
        echo "<h1 style='text-align:center; padding-top:100px;'>404 - Page introuvable</h1>";
        break;
}
?>