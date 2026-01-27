<?php require_once 'Views/partials/header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold" style="color: #8B2635;">Nos Menus de Saison</h1>
        <p class="lead text-muted">Des créations uniques pour tous les goûts.</p>
    </div>

    <div class="row">
        <?php if (empty($menus)): ?>
            <div class="col-12 text-center">
                <div class="alert alert-warning">Aucun menu disponible pour le moment.</div>
            </div>
        <?php else: ?>
            
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        
                        <?php 
                            $imgName = $menu['image_principale'];
                            // Chemin vers tes images (Assure-toi que le dossier existe)
                            $imgPath = "assets/images/menus/" . $imgName;
                            
                            // Si l'image est vide ou introuvable, on met une image par défaut
                            if(empty($imgName) || !file_exists($imgPath)) {
                                $imgPath = "https://via.placeholder.com/400x250?text=Menu"; 
                            }
                        ?>
                        <img src="<?php echo $imgPath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($menu['titre']); ?>" style="height: 250px; object-fit: cover;">

                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-dark"><?php echo htmlspecialchars($menu['theme_nom']); ?></span>
                                <span class="badge" style="background-color: #D8A85E; color: #fff;">
                                    <?php echo htmlspecialchars($menu['regime_nom']); ?>
                                </span>
                            </div>

                            <h5 class="card-title fw-bold" style="color: #8B2635;"><?php echo htmlspecialchars($menu['titre']); ?></h5>
                            
                            <p class="card-text text-muted flex-grow-1">
                                <?php echo htmlspecialchars($menu['description']); ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fw-bold fs-5"><?php echo number_format($menu['prix_par_personne'], 2); ?> € / pers.</span>
                                
                                <a href="index.php?page=menu&id=<?php echo $menu['menu_id']; ?>" class="btn btn-outline-danger">
                                    Voir Détail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>