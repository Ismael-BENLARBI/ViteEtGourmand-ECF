<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/detail_menu.css">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="detail-card">
                <div class="row">
                    
                    <div class="col-md-6 vertical-divider">
                        
                        <?php 
                            $imgName = $menu['image_principale'];
                            $imgPath = "assets/images/menu/" . $imgName;
                            if(empty($imgName) || !file_exists($imgPath)) { $imgPath = "https://via.placeholder.com/600x600?text=Menu"; }
                        ?>
                        <div class="main-image-container">
                            <img src="<?php echo $imgPath; ?>" class="main-image clickable" alt="<?php echo htmlspecialchars($menu['titre']); ?>" onclick="openModal(this.src)">
                        </div>

                        <div class="thumbnails-row">
                            <img src="<?php echo $imgPath; ?>" class="thumb-img" alt="Vue principale" onclick="changeMainImage(this.src)">
                            <img src="assets/images/menu/menu_entreprisee.png" class="thumb-img" alt="Test Image 2" onclick="changeMainImage(this.src)">
                            <img src="assets/images/menu/menu_vege.png" class="thumb-img" alt="Test Image 3" onclick="changeMainImage(this.src)">
                        </div>

                        <div class="composition-section">
                            <h3 class="composition-title">Composition du Menu</h3>
                            
                            <?php foreach ($plats as $plat): ?>
                                <div class="mb-3">
                                    <div class="plat-category"><?php echo htmlspecialchars($plat['categorie']); ?></div>
                                    <div class="plat-name"><?php echo htmlspecialchars($plat['titre_plat']); ?></div>
                                    
                                    <div class="allergenes">
                                        (Allergène : Gluten, Œufs, Laitage...)
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-md-6 right-col-content">
                        
                        <h1 class="menu-title-detail">
                            <?php echo htmlspecialchars($menu['titre']); ?>
                        </h1>

                        <div class="badges-row">
                            <div class="badge-pill-theme">Thème : <?php echo htmlspecialchars($menu['theme_nom']); ?></div>
                            <div class="badge-pill-regime"><?php echo htmlspecialchars($menu['regime_nom']); ?></div>
                        </div>

                        <div class="price-large">
                            <?php echo number_format($menu['prix_par_personne'], 0, ',', ' '); ?>€/personne
                        </div>
                        <div class="min-personnes">*Dès <?php echo $menu['nombre_personne_min'] ?? 8; ?> personnes*</div>

                        <div class="stock-alert">
                            ⚠️ STOCK : Plus que <?php echo $menu['quantite_restante']; ?> menus disponibles !
                        </div>

                        <div class="info-box">
                            <div class="info-box-title">INFOS LIVRAISON</div>
                            <div class="info-box-text">
                                Bordeaux (33000) : GRATUIT<br>
                                Hors Bordeaux : 5,00€ + 0,59€/km
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="qty-selector">
                                <span class="info-box-title m-0">QUANTITÉ : </span>
                                <button class="qty-btn">-</button>
                                <span><?php echo $menu['nombre_personne_min'] ?? 8; ?></span>
                                <button class="qty-btn">+</button>
                                <span class="ms-2 text-muted" style="font-size:0.8rem;">Hors Bordeaux : 5,00€ + 0,59€/km</span>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-box-title">RÉDUCTION GROUPE</div>
                            <div class="info-box-text">
                                -10% si vous commandez pour + de 13 pers.
                            </div>
                        </div>

                        <button class="btn-panier">
                            AJOUTER AU PANIER : COMMANDER
                        </button>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div id="imageModal" class="modal-overlay" onclick="closeModalIfOutside(event)">
  <span class="close-modal" onclick="closeModal()">&times;</span>
  <img class="modal-content-img" id="expandedImg">
</div>

<script>
    // Fonction pour ouvrir le modal
    function openModal(src) {
        const modal = document.getElementById("imageModal");
        const modalImg = document.getElementById("expandedImg");
        
        // On affiche le modal (qui passe de display:none à display:flex grâce au CSS)
        modal.style.display = "flex";
        // On met la source de l'image cliquée dans l'image du modal
        modalImg.src = src;
        // On empêche le scroll de la page derrière
        document.body.style.overflow = "hidden";
    }

    // Fonction pour fermer le modal
    function closeModal() {
        const modal = document.getElementById("imageModal");
        modal.style.display = "none";
        // On réactive le scroll de la page
        document.body.style.overflow = "auto";
    }

    // Bonus : Fermer si on clique sur le fond noir (hors de l'image)
    function closeModalIfOutside(event) {
        const modalImg = document.getElementById("expandedImg");
        // Si l'élément cliqué n'est PAS l'image elle-même, on ferme
        if (event.target !== modalImg) {
            closeModal();
        }
    }

    // --- NOUVELLE FONCTION : Changer l'image principale au clic sur une vignette ---
    function changeMainImage(src) {
        // 1. On cible la grande image
        const mainImg = document.querySelector('.main-image');
        
        // 2. On remplace sa source par celle de la vignette cliquée
        mainImg.src = src;
        
        // Bonus : Petite animation de transition (fade in)
        mainImg.style.opacity = 0;
        setTimeout(() => {
            mainImg.style.opacity = 1;
        }, 100);
    }
</script>

<?php require_once 'Views/partials/footer.php'; ?>