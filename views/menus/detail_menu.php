<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/detail_menu.css">

<div class="container py-5">
    
    <div class="mb-4">
        <a href="index.php?page=menus" class="text-muted text-decoration-none fw-bold">
            <i class="fa-solid fa-arrow-left"></i> Retour aux menus
        </a>
    </div>

    <div class="detail-card">
        <div class="row">
            
            <div class="col-lg-6 vertical-divider">
                
                <div class="main-image-container">
                    <?php 
                        $imgMain = !empty($menu['image_principale']) ? "assets/images/menu/" . $menu['image_principale'] : "assets/images/default.jpg";
                    ?>
                    <img src="<?php echo $imgMain; ?>" alt="<?php echo htmlspecialchars($menu['titre']); ?>" class="main-image clickable" onclick="openModal(this)">
                </div>

                <div class="gallery-grid">
                    <?php if(!empty($menu['image_entree'])): ?>
                        <img src="assets/images/menu/<?php echo $menu['image_entree']; ?>" class="gallery-thumb" onclick="changeMainImage(this)" alt="Entrée">
                    <?php endif; ?>
                    
                    <?php if(!empty($menu['image_plat'])): ?>
                        <img src="assets/images/menu/<?php echo $menu['image_plat']; ?>" class="gallery-thumb" onclick="changeMainImage(this)" alt="Plat">
                    <?php endif; ?>
                    
                    <?php if(!empty($menu['image_dessert'])): ?>
                        <img src="assets/images/menu/<?php echo $menu['image_dessert']; ?>" class="gallery-thumb" onclick="changeMainImage(this)" alt="Dessert">
                    <?php endif; ?>
                </div>

                <div class="menu-composition">
                    <h3 class="composition-title">Composition du Menu</h3>
                    
                    <?php if(!empty($menu['description_entree'])): ?>
                    <div class="course-block">
                        <div class="course-name">Entrée</div>
                        <p class="course-desc"><?php echo nl2br(htmlspecialchars($menu['description_entree'])); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($menu['description_plat'])): ?>
                    <div class="course-block">
                        <div class="course-name">Plat</div>
                        <p class="course-desc"><?php echo nl2br(htmlspecialchars($menu['description_plat'])); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($menu['description_dessert'])): ?>
                    <div class="course-block">
                        <div class="course-name">Dessert</div>
                        <p class="course-desc"><?php echo nl2br(htmlspecialchars($menu['description_dessert'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="col-lg-6">
                <div class="right-col-content">
                    
                    <h1 class="menu-title-detail"><?php echo htmlspecialchars($menu['titre']); ?></h1>
                    
                    <div class="badges-row">
                        <?php if(!empty($menu['theme_nom'])): ?>
                            <span class="badge-pill-theme">Thème : <?php echo htmlspecialchars($menu['theme_nom']); ?></span>
                        <?php endif; ?>

                        <?php if(!empty($menu['regime_nom'])): ?>
                            <span class="badge-pill-regime"><?php echo htmlspecialchars($menu['regime_nom']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-2">
                        <span class="price-large"><?php echo number_format($menu['prix_par_personne'], 2); ?>€</span>
                        <span class="text-muted fs-5">/personne</span>
                    </div>
                    <p class="min-personnes">*Dès <?php echo $menu['nombre_personne_min']; ?> personnes*</p>

                    <div class="stock-alert">
                         ⚠️ STOCK : Plus que <?php echo $menu['quantite_restante'] ?? 50; ?> menus disponibles !
                    </div>

                    <div class="info-box">
                        <div class="info-box-title">Infos Livraison</div>
                        <div class="info-box-text">
                            📍 Bordeaux (33000) : <strong>GRATUIT</strong><br>
                            🚚 Hors Bordeaux : 5,00€ + 0,59€/km
                        </div>
                    </div>

                    <div class="info-box d-flex justify-content-between align-items-center">
                        <div class="info-box-title mb-0">Quantité :</div>
                        <div class="qty-selector">
                            <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>
                            <input type="number" id="qtyInput" value="<?php echo $menu['nombre_personne_min']; ?>" min="<?php echo $menu['nombre_personne_min']; ?>" style="width: 50px; text-align: center; border:none; background: transparent; font-weight: bold;">
                            <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-box-title">RÉDUCTION GROUPE</div>
                        <div class="info-box-text">
                            -10% si vous commandez pour + de 13 pers.
                        </div>
                    </div>

                    <form action="index.php?page=panier_add" method="POST">
                        <input type="hidden" name="menu_id" value="<?php echo $menu['menu_id']; ?>">
                        <input type="hidden" name="quantite" id="formQty" value="<?php echo $menu['nombre_personne_min']; ?>">
                        
                        <button type="submit" class="btn-panier">
                            Ajouter au panier : Commander
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Changer l'image principale au clic sur une miniature
    function changeMainImage(element) {
        document.querySelector('.main-image').src = element.src;
    }

    // Gestion du bouton + et - pour la quantité
    function updateQty(change) {
        let input = document.getElementById('qtyInput');
        let formInput = document.getElementById('formQty');
        let min = parseInt(input.min);
        let newVal = parseInt(input.value) + change;
        
        if(newVal >= min) {
            input.value = newVal;
            formInput.value = newVal; // On met à jour le champ caché pour le formulaire
        }
    }
</script>

<div id="imageModal" class="modal-overlay" onclick="closeModal()">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <img class="modal-content-img" id="fullImage">
</div>

<script>
    // 1. Ouvrir la modale (Zoom)
    function openModal(element) {
        var modal = document.getElementById("imageModal");
        var modalImg = document.getElementById("fullImage");
        
        modal.style.display = "flex"; // On affiche la boite noire
        modalImg.src = element.src;   // On met l'image cliquée dedans
    }

    // 2. Fermer la modale
    function closeModal() {
        document.getElementById("imageModal").style.display = "none";
    }

    // 3. Changer l'image principale au clic sur une miniature
    function changeMainImage(element) {
        document.querySelector('.main-image').src = element.src;
    }

    // 4. Gestion de la quantité (+ / -)
    function updateQty(change) {
        let input = document.getElementById('qtyInput');
        let formInput = document.getElementById('formQty');
        let min = parseInt(input.min);
        let newVal = parseInt(input.value) + change;
        
        if(newVal >= min) {
            input.value = newVal;
            formInput.value = newVal;
        }
    }
</script>

<?php require_once 'Views/partials/footer.php'; ?>