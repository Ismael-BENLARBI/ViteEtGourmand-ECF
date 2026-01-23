<?php
require_once 'config/db.php';
session_start();

$page = $_GET['page'] ?? 'home';

// Fichier: Views/home/index.php

$isHomepage = false; // C'est cette ligne qui rend le header transparent !
require_once 'views/partials/header.php'; 
?>

<div style="background: url('chemin/vers/ton/image_hero.jpg') no-repeat center center; background-size: cover; height: 100vh; padding-top: 100px;">
    <div class="container text-white">
        <h1>L'excellence traiteur livrée chez vous !</h1>
        </div>
</div>

<?php require_once 'views/partials/footer.php'; ?>