<?php
require_once 'config/db.php';
session_start();

$page = $_GET['page'] ?? 'home';

switch($page) {
    case 'home':
        $content = "<h1>Bienvenue chez Vite & Gourmand</h1><p>Ceci est la page d'accueil.</p>";
        break;
        
    case 'menus':
        $content = "<h1>Nos Menus</h1><p>Liste des menus à venir ici.</p>";
        break;
        
    case 'contact':
        $content = "<h1>Contact</h1><p>Formulaire de contact à venir ici.</p>";
        break;
        
    default:
        http_response_code(404);
        $content = "<h1>404 - Page introuvable</h1>";
        break;
}

echo $content;
?>