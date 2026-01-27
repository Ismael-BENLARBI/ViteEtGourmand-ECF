<?php 

$isHomepage = true; 
require_once 'views/partials/header.php'; 
?>

<link rel="stylesheet" href="assets/css/home.css">

<div class="hero-wrapper">
    <img src="assets/images/layout/Image_Hero.png" alt="Plat signature" class="hero-image">
</div>

<div class="container hero-content">
    <h1 class="hero-title">L'excellence traiteur livrée chez vous !</h1>
    
    <p class="hero-text">
        De Noël à Pâques, savourez des cartes thématiques conçues pour sublimer vos tables.
        Profitez d'un service traiteur sur-mesure, livré directement à votre domicile sur Bordeaux et ses environs.
    </p>

    <a href="index.php?page=menus" class="btn btn-hero">VOIR CARTES</a>
</div>

<section class="py-5">
    <div class="container py-4">
        <div class="row align-items-center">
            
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="section-title">À PROPOS</h2>
                
                <div class="about-box">
                    <p class="fw-bold mb-3">
                        Passionnés par la gastronomie, nous avons fondé Vite & Gourmand pour amener l'excellence d'un traiteur artisanal directement chez vous.
                    </p>
                    <p class="mb-0">
                        Julie crée des menus de saison savoureux, tandis que José assure une préparation et une livraison irréprochables.
                        Ensemble, nous cuisinons des produits frais et locaux pour sublimer vos moments d'exception tout au long de l'année.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-6">
            <img src="assets/images/team/josé_julie.jpg" alt="Julie et José" class="about-img">
            </div>
            
        </div>
    </div>
</section>

<section class="py-5 pb-5">
    <div class="container">
        <div class="row g-4 mt-2">
            
            <div class="col-md-4">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="fst-italic">
                        "Excellent service de la part de José et son équipe ! La commande pour mes 15 convives a été livrée pile à l'heure sur Bordeaux, avec une présentation soignée."
                    </p>
                    <div class="review-author">Johnny S</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="fst-italic">
                        "Enfin un traiteur qui propose de vraies options savoureuses pour tout le monde. Nous avons testé le menu Végétarien thématique et tout le monde a été conquis."
                    </p>
                    <div class="review-author">Ada W</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card">
                    <div class="review-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="fst-italic">
                        "Une expérience gastronomique incroyable à domicile ! Le menu de Noël était d'une finesse rare, avec des produits d'une fraîcheur irréprochable."
                    </p>
                    <div class="review-author">Chris R</div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once 'views/partials/footer.php'; ?>