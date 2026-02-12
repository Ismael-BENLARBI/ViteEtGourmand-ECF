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
                    <img src="<?php echo $imgMain; ?>" id="mainImage" alt="<?php echo htmlspecialchars($menu['titre']); ?>" class="main-image clickable" onclick="openModal(this)">
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

                    <?php 
                        $stock = $menu['quantite_restante'] ?? 0;
                        $minReq = $menu['nombre_personne_min'];
                        // Est-ce qu'on peut commander ? (Stock doit être >= au minimum requis)
                        $isOrderable = ($stock >= $minReq);
                    ?>

                    <div class="stock-alert" style="<?php echo (!$isOrderable) ? 'color:red; border-color:red; background-color:#ffe6e6;' : ''; ?>">
                        <?php if($stock == 0): ?>
                            ❌ RUPTURE DE STOCK DÉFINITIVE
                        <?php elseif(!$isOrderable): ?>
                            ⚠️ Stock insuffisant (<?php echo $stock; ?> restants pour min. <?php echo $minReq; ?> pers.)
                        <?php else: ?>
                            ⚠️ STOCK : Plus que <?php echo $stock; ?> menus disponibles !
                        <?php endif; ?>
                    </div>

                    <?php if(!empty($menu['conditions'])): ?>
                        <div class="alert alert-warning mt-3 mb-3 border-0 shadow-sm" style="background-color: #fff3cd; color: #856404;">
                            <div class="d-flex">
                                <div class="me-3 fs-4">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                </div>
                                <div>
                                    <strong class="text-uppercase small">Conditions importantes :</strong><br>
                                    <?php echo nl2br(htmlspecialchars($menu['conditions'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="info-box">
                        <div class="info-box-title">Infos Livraison</div>
                        <div class="info-box-text">
                            📍 Bordeaux (33000) : <strong>GRATUIT</strong><br>
                            🚚 Hors Bordeaux : 5,00€ + 0,59€/km
                        </div>
                    </div>

                    <?php if($isOrderable): ?>
                        
                        <div class="info-box d-flex justify-content-between align-items-center">
                            <div class="info-box-title mb-0">Quantité :</div>
                            <div class="qty-selector">
                                <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>
                                <input type="number" id="qtyInput" 
                                       value="<?php echo $minReq; ?>" 
                                       min="<?php echo $minReq; ?>" 
                                       max="<?php echo $stock; ?>"
                                       style="width: 50px; text-align: center; border:none; background: transparent; font-weight: bold;" readonly>
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
                            <input type="hidden" name="quantite" id="formQty" value="<?php echo $minReq; ?>">
                            
                            <button type="submit" class="btn-panier">
                                <i class="fa-solid fa-cart-plus me-2"></i> Ajouter au panier
                            </button>
                        </form>

                    <?php else: ?>
                        
                        <div class="mt-4">
                            <button disabled class="btn w-100 py-3 fw-bold text-white" style="background-color: #ccc; cursor: not-allowed; border-radius: 50px;">
                                <i class="fa-solid fa-ban me-2"></i> Stock Insuffisant
                            </button>
                            <small class="d-block text-center mt-2 text-muted">Ce menu n'est plus disponible en quantité suffisante.</small>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="modal-overlay" onclick="closeModal()" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9);">
    <span class="close-modal" onclick="closeModal()" style="position: absolute; top: 15px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; cursor: pointer;">&times;</span>
    <img class="modal-content-img" id="fullImage" style="margin: auto; display: block; width: 80%; max-width: 700px; margin-top: 100px;">
</div>

<script>
    // 1. Gestion de la quantité et mise à jour du formulaire
    function updateQty(change) {
        const input = document.getElementById('qtyInput');
        const formInput = document.getElementById('formQty'); // Le champ caché qui part au panier
        
        let currentVal = parseInt(input.value);
        let min = parseInt(input.getAttribute('min'));
        let max = parseInt(input.getAttribute('max'));
        
        let newVal = currentVal + change;

        if (newVal >= min && newVal <= max) {
            input.value = newVal;
            formInput.value = newVal; // On met à jour le champ caché
        }
    }

    // 2. Changer l'image principale au clic
    function changeMainImage(element) {
        document.getElementById('mainImage').src = element.src;
    }

    // 3. Modale (Zoom)
    function openModal(element) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById("fullImage");
        modal.style.display = "block";
        modalImg.src = element.src;
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = "none";
    }
</script>

<?php require_once 'Views/partials/footer.php'; ?>