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
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="theme" value="all" id="theme_all" checked>
                            <label class="form-check-label" for="theme_all">Tout voir</label>
                        </div>
                        <?php foreach($themes as $theme): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="theme" 
                                value="<?php echo $theme['theme_id']; ?>" 
                                id="theme_<?php echo $theme['theme_id']; ?>">
                                <label class="form-check-label" for="theme_<?php echo $theme['theme_id']; ?>">
                                    <?php echo htmlspecialchars($theme['libelle']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="filter-column">
                        <h5>Régime alimentaire</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="regime" value="all" checked>
                            <label class="form-check-label">Tous</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="regime" value="Végétarien">
                            <label class="form-check-label">Végétarien</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="regime" value="Halal">
                            <label class="form-check-label">Halal</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="regime" value="Sans Gluten">
                            <label class="form-check-label">Sans Gluten</label>
                        </div>
                    </div>

                    <div class="filter-column">
                        <h5>Minimum Personnes</h5>
                        <input type="number" id="filter-pers" name="personnes" class="form-control form-control-custom" placeholder="Ex: 8">
                    </div>

                    <div class="filter-column">
                        <h5>Prix Max: <span id="price-display">100</span>€</h5>
                        <input type="range" id="filter-prix" name="prix" class="form-range" min="0" max="100" value="100" 
                        oninput="document.getElementById('price-display').innerText = this.value">
                        <div class="d-flex justify-content-between text-white" style="font-size:0.8rem;">
                            <span>0€</span><span>100€</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row" id="menus-container">
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
                                $imgPath = "assets/images/menu/" . $imgName;
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
                            <p class="card-text text-muted small">
                                *Dès <?php echo $menu['nombre_personne_min']; ?> personnes*
                            </p>
                            
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
        content.style.display = "block";
        arrow.classList.add("open");
    } else {
        content.style.display = "none";
        arrow.classList.remove("open");
    }
}
</script>

<script>
function updateMenus() {
    const themeInput = document.querySelector('input[name="theme"]:checked');
    const themeVal = themeInput ? themeInput.value : 'all';

    const regimeInput = document.querySelector('input[name="regime"]:checked');
    const regimeVal = regimeInput ? regimeInput.value : 'all';

    const prixVal = document.getElementById('filter-prix').value;
    const persVal = document.getElementById('filter-pers').value;

    const url = `index.php?page=api_menus&theme=${themeVal}&regime=${encodeURIComponent(regimeVal)}&prix=${prixVal}&pers=${persVal}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('menus-container');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted fs-4">Aucun menu ne correspond à vos critères.</p></div>';
                return;
            }

            data.forEach(menu => {
                let imgPath = "https://via.placeholder.com/400x300?text=Menu";
                if (menu.image_principale) {
                    imgPath = "assets/images/menu/" + menu.image_principale;
                }
                const prix = parseFloat(menu.prix_par_personne).toFixed(2).replace('.', ',');

                const cardHTML = `
                <div class="col-md-4 mb-5">
                    <div class="menu-card">
                        <div class="menu-img-container">
                            <img src="${imgPath}" class="menu-img" alt="${menu.titre}">
                        </div>
                        <div class="card-body-custom">
                            <div class="badges-container">
                                <span class="badge-theme">${menu.theme_nom || ''}</span>
                                <span class="badge-regime">${menu.regime_nom || ''}</span>
                            </div>
                            <h3 class="menu-title">${menu.titre}</h3>
                            <p class="menu-min-person">*Dès ${menu.nombre_personne_min || 6} personnes*</p>
                            <div class="menu-price">${prix}€/personne</div>
                            <a href="index.php?page=menu&id=${menu.menu_id}" class="btn-details">VOIR DETAILS</a>
                        </div>
                    </div>
                </div>`;
                container.innerHTML += cardHTML;
            });
        })
        .catch(error => console.error('Erreur AJAX:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.filters-grid input');
    inputs.forEach(input => {
        input.addEventListener('change', updateMenus);
        input.addEventListener('input', updateMenus);
    });
});
</script>

<?php require_once 'Views/partials/footer.php'; ?>