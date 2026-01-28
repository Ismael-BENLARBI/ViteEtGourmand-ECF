<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/menus.css">

<div class="container py-5">

    <h1 class="page-title">Cartes des menus</h1>
    
    <div class="filters-container">
        <div class="filters-header" onclick="toggleFilters()">
            <h3 class="filters-title">Filtres</h3>
            <span id="arrow-icon" class="toggle-arrow">▼</span>
        </div>
        
        <div id="filters-content" class="filters-content" style="display: none;"> <form action="index.php" method="GET">
                <input type="hidden" name="page" value="menus">
                
                <div class="filters-grid">
                    <div class="filter-column">
                        <h5>Thème</h5>
                        <?php foreach($themes as $theme): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="theme" 
                                       value="<?php echo $theme['theme_id']; ?>" 
                                       id="theme_<?php echo $theme['theme_id']; ?>"
                                       <?php echo ($filtreTheme == $theme['theme_id']) ? 'checked' : ''; ?>
                                       onchange="this.form.submit()">
                                <label class="form-check-label" for="theme_<?php echo $theme['theme_id']; ?>">
                                    <?php echo htmlspecialchars($theme['libelle']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="theme" value="all" id="theme_all" 
                                   <?php echo ($filtreTheme == 'all') ? 'checked' : ''; ?>
                                   onchange="this.form.submit()">
                            <label class="form-check-label" for="theme_all">Tout voir</label>
                        </div>
                    </div>

                    <div class="filter-column">
                        <h5>Régime alimentaire</h5>
                        <div class="form-check"><input class="form-check-input" type="checkbox" disabled><label class="form-check-label">Classique</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" disabled><label class="form-check-label">Végétarien</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" disabled><label class="form-check-label">Végan</label></div>
                    </div>

                    <div class="filter-column">
                        <h5>Nombre de personnes</h5>
                        <input type="number" class="form-control form-control-custom" placeholder="Saisir un nombre...">
                    </div>

                    <div class="filter-column">
                        <h5>Prix</h5>
                        <input type="range" class="form-range" min="0" max="100">
                        <div class="d-flex justify-content-between text-white" style="font-size:0.8rem;">
                            <span>0€</span><span>100€</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <?php if (empty($menus)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-4">Aucun menu trouvé.</p>
            </div>
        <?php else: ?>
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4 mb-5">
                    <div class="menu-card">
                        <div class="menu-img-container">
                            <?php 
                                $imgName = $menu['image_principale'];
                                $imgPath = "assets/images/menus/" . $imgName;
                                if(empty($imgName) || !file_exists($imgPath)) { $imgPath = "https://via.placeholder.com/400x300?text=Menu"; }
                            ?>
                            <img src="<?php echo $imgPath; ?>" class="menu-img" alt="<?php echo htmlspecialchars($menu['titre']); ?>">
                        </div>

                        <div class="card-body-custom">
                            <div class="badges-container">
                                <span class="badge-theme">Thème : <?php echo htmlspecialchars($menu['theme_nom']); ?></span>
                                <span class="badge-regime"><?php echo htmlspecialchars($menu['regime_nom']); ?></span>
                            </div>

                            <h3 class="menu-title"><?php echo htmlspecialchars($menu['titre']); ?></h3>
                            <p class="menu-min-person">*Dès 6 personnes*</p>
                            
                            <div class="menu-price">
                                <?php echo number_format($menu['prix_par_personne'], 2, ',', ' '); ?>€/personne
                            </div>
                            
                            <a href="index.php?page=menu&id=<?php echo $menu['menu_id']; ?>" class="btn-details">
                                VOIR DETAILS
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleFilters() {
    var content = document.getElementById("filters-content");
    var arrow = document.getElementById("arrow-icon");
    
    if (content.style.display === "none") {
        content.style.display = "block"; // On affiche
        arrow.classList.add("open");     // On tourne la flèche
    } else {
        content.style.display = "none";  // On cache
        arrow.classList.remove("open");  // On remet la flèche normale
    }
}
</script>

<?php require_once 'Views/partials/footer.php'; ?>