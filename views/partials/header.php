<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/header.css">
</head>
<body>

<?php
$bgClass = (isset($isHomepage) && $isHomepage === true) ? 'navbar-transparent' : 'navbar-solid';
?>

<nav class="navbar navbar-expand-lg navbar-custom <?php echo $bgClass; ?>">
  <div class="container justify-content-between justify-content-lg-center">
    
    <a class="navbar-brand" href="index.php?page=home">
        <img src="assets/images/icons/logo.png" alt="Logo Vite et Gourmand">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse flex-grow-0" id="navbarNav">
      <ul class="navbar-nav align-items-center ms-lg-5">
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=home">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=menus">Nos Menus</a>
        </li>
        
        <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=profil">Mon Compte</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=logout">Déconnexion</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=login">Connexion</a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="index.php?page=contact">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content" style="<?php echo (isset($isHomepage) && $isHomepage) ? '' : 'padding-top: 80px;'; ?>">