<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* CSS Personnalisé pour coller à la maquette */
        body { font-family: 'montserrat', sans-serif; }
        
        /* Titre "Vite & Gourmand" */
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #333 !important;
        }

        /* Liens du menu en majuscules */
        .nav-link {
            text-transform: uppercase;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333 !important; 
            letter-spacing: 1px;
        }

        /* Couleur Or/Moutarde des boutons (selon maquette) */
        .btn-gold {
            background-color: #d4af37; 
            color: white;
            border: none;
        }
        .btn-gold:hover { background-color: #b5952f; color: white; }

        /* Gestion du Header Transparent (Accueil) vs Solide (Autres) */
        .navbar-transparent {
            background-color: transparent !important;
            position: absolute;
            width: 100%;
            z-index: 1000;
            box-shadow: none;
        }
        
        .navbar-solid {
            background-color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }
    </style>
</head>
<body>

<?php
// Logique : Si la variable $isHomepage est définie et vraie, on met le header transparent
// Sinon, on met le header blanc classique.
$navClass = (isset($isHomepage) && $isHomepage === true) ? 'navbar-transparent' : 'navbar-solid';
?>

<nav class="navbar navbar-expand-lg navbar-light <?php echo $navClass; ?> py-3">
  <div class="container">
    <a class="navbar-brand" href="/">Vite&Gourmand</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item mx-2">
          <a class="nav-link" href="/">Accueil</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link" href="/menus">Cartes des menus</a>
        </li>
        
        <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item mx-2">
                <a class="nav-link btn btn-sm btn-outline-dark px-3" href="/profil">Mon Compte</a>
            </li>
        <?php else: ?>
            <li class="nav-item mx-2">
                <a class="nav-link" href="/login">Connexion</a>
            </li>
        <?php endif; ?>

        <li class="nav-item mx-2">
          <a class="nav-link" href="/contact">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>