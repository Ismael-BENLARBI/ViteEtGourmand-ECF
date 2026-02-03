<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/admin_dashboard.css">

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="dashboard-title">Tableau de Bord</h1>
            <span class="dashboard-subtitle">Espace Administrateur</span>
        </div>
    </div>

    <ul class="nav nav-pills mb-4 gap-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold px-4 py-2" id="pills-commandes-tab" data-bs-toggle="pill" data-bs-target="#pills-commandes" type="button">
                📦 Commandes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-4 py-2" id="pills-menus-tab" data-bs-toggle="pill" data-bs-target="#pills-menus" type="button">
                🍽️ Menus
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-commandes" role="tabpanel">
            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ref</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($commandes)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        Aucune commande pour le moment.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($commandes as $cmd): ?>
                                <tr>
                                    <td class="fw-bold text-dark">#<?php echo $cmd['numero_commande']; ?></td>
                                    <td>
                                        <span class="d-block fw-bold"><?php echo htmlspecialchars($cmd['prenom'] . ' ' . $cmd['nom']); ?></span>
                                        <small class="text-muted"><?php echo htmlspecialchars($cmd['ville_livraison']); ?></small>
                                    </td>
                                    <td><?php echo (new DateTime($cmd['date_commande']))->format('d/m H:i'); ?></td>
                                    <td class="menu-price"><?php echo number_format($cmd['prix_total'], 2); ?>€</td>
                                    
                                    <td>
                                        <form action="index.php?page=admin_commande_status" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $cmd['commande_id']; ?>">
                                            <select name="statut" class="form-select form-select-sm status-select <?php echo $cmd['statut']; ?>" 
                                                    onchange="this.form.submit()">
                                                <option value="en_attente" <?php echo $cmd['statut'] == 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                                <option value="validee" <?php echo $cmd['statut'] == 'validee' ? 'selected' : ''; ?>>Validée</option>
                                                <option value="livree" <?php echo $cmd['statut'] == 'livree' ? 'selected' : ''; ?>>Livrée</option>
                                                <option value="annulee" <?php echo $cmd['statut'] == 'annulee' ? 'selected' : ''; ?>>Annulée</option>
                                            </select>
                                        </form>
                                    </td>
                                    
                                    <td class="text-end">
                                        <a href="index.php?page=commande_details&id=<?php echo $cmd['commande_id']; ?>" 
                                           class="action-btn btn-view" title="Voir les détails">
                                            <i class="fa-solid fa-eye"></i>
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

        <div class="tab-pane fade" id="pills-menus" role="tabpanel">
            
            <div class="d-flex justify-content-end mb-3">
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
                                               <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="index.php?page=admin_menu_delete&id=<?php echo $menu['menu_id']; ?>" 
                                               class="action-btn btn-delete"
                                               title="Supprimer"
                                               onclick="return confirm('Attention : suppression définitive du menu <?php echo addslashes($menu['titre']); ?> ?');">
                                               <i class="fa-solid fa-trash"></i>
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

    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>