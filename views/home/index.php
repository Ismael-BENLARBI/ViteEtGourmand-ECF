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

<section class="py-5 section-avis">
    <div class="container">
        <h2 class="text-center mb-5 avis-title">Les derniers avis gourmands</h2>
        
        <div class="row g-4">
            <?php if(empty($avisRecents)): ?>
                <div class="col-12 text-center text-muted">
                    <p>Soyez le premier à donner votre avis !</p>
                </div>
            <?php else: ?>
                <?php foreach($avisRecents as $avis): ?>
                <div class="col-md-4">
                    <div class="card h-100 card-avis">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 avis-stars">
                                <?php for($i=0; $i<$avis['note']; $i++) echo '★'; ?>
                                <?php for($i=$avis['note']; $i<5; $i++) echo '☆'; ?>
                            </div>
                            
                            <p class="card-text fst-italic text-muted">
                                "<?php echo htmlspecialchars($avis['description']); ?>"
                            </p>
                            
                            <hr class="avis-separator">
                            
                            <h6 class="avis-author"><?php echo htmlspecialchars($avis['prenom']); ?></h6>
                            <small class="avis-menu-name">A goûté : <?php echo htmlspecialchars($avis['titre']); ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'views/partials/footer.php'; ?>