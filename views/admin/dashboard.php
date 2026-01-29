<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/admin_dashboard.css">

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="dashboard-title">Gestion des Menus</h1>
            <span class="dashboard-subtitle">Espace Administrateur</span>
        </div>
        <a href="index.php?page=admin_menu_add" class="btn-add-menu">
            <span>+</span> Nouveau Menu
        </a>
    </div>

    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Visuel</th>
                        <th>Détails du Menu</th>
                        <th>Prix / Pers.</th>
                        <th>Thème</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($menus)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Aucun menu pour le moment. Commencez par en ajouter un !
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($menus as $menu): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $img = !empty($menu['image_principale']) 
                                            ? "assets/images/menu/" . $menu['image_principale'] 
                                            : "https://via.placeholder.com/70x70?text=Menu";
                                    ?>
                                    <img src="<?php echo $img; ?>" alt="Menu" class="menu-thumb">
                                </td>

                                <td>
                                    <span class="menu-title"><?php echo htmlspecialchars($menu['titre']); ?></span>
                                    <small class="text-muted">Min. <?php echo $menu['nombre_personne_min']; ?> personnes</small>
                                </td>

                                <td>
                                    <span class="menu-price"><?php echo number_format($menu['prix_par_personne'], 2); ?>€</span>
                                </td>

                                <td>
                                    <span class="badge-custom">
                                        <?php echo htmlspecialchars($menu['theme_nom'] ?? 'Aucun'); ?>
                                    </span>
                                </td>

                                <td class="text-end">
                                    <a href="index.php?page=admin_menu_edit&id=<?php echo $menu['menu_id']; ?>" 
                                       class="action-btn btn-edit" title="Modifier">
                                       ✎
                                    </a>
                                    <a href="index.php?page=admin_menu_delete&id=<?php echo $menu['menu_id']; ?>" 
                                       class="action-btn btn-delete"
                                       title="Supprimer"
                                       onclick="return confirm('Attention : suppression définitive du menu <?php echo addslashes($menu['titre']); ?> ?');">
                                       🗑
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once 'Views/partials/footer.php'; ?>