<?php
// index.php : Le "Portier" de ton site

session_start(); // On démarre la session pour les futurs logins

// On regarde si une "page" est demandée dans l'URL, sinon on va sur "home"
$page = $_GET['page'] ?? 'home';

// Routage simple
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
        // Page 404
        http_response_code(404);
        $content = "<h1>404 - Page introuvable</h1>";
        break;
}

// Affichage simple pour tester ce soir
echo $content;
?>