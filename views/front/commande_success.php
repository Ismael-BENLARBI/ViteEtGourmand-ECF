<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/success.css">

<div class="container py-5">
    
    <div class="success-card text-center">
        <div class="icon-success">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <h1 class="fw-bold mb-3">Merci pour votre commande !</h1>
        
        <p class="text-muted mb-5" style="font-size: 1.1rem;">
            Votre demande a bien été enregistrée.<br>
            Notre équipe va commencer la préparation de vos menus gourmands avec soin.
        </p>

        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
            <a href="index.php?page=menus" class="btn btn-outline-dark rounded-pill px-4 py-2">
                Retour aux menus
            </a>
            <a href="index.php?page=compte" class="btn rounded-pill px-4 py-2 text-white" style="background: #D8A85E; border: none;">
                Voir mon historique
            </a>
        </div>
    </div>

</div>

<?php require_once 'Views/partials/footer.php'; ?>