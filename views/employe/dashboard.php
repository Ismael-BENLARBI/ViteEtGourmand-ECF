<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/admin_dashboard.css">

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="dashboard-title">Espace Employé</h1>
            <span class="dashboard-subtitle">Gestion opérationnelle</span>
        </div>
        <div>
            <a href="index.php?page=logout" class="btn btn-outline-danger rounded-pill px-4">
                <i class="fa-solid fa-power-off me-2"></i> Déconnexion
            </a>
        </div>
    </div>

    <ul class="nav nav-pills mb-4 gap-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold px-4 py-2" data-bs-toggle="pill" data-bs-target="#pills-commandes">
                📦 Commandes
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-2" data-bs-toggle="pill" data-bs-target="#pills-menus">
                🍽️ Menus
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-2" data-bs-toggle="pill" data-bs-target="#pills-avis">
                <i class="fa-solid fa-star me-2"></i> Avis
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-2" data-bs-toggle="pill" data-bs-target="#pills-horaires">
                <i class="fa-solid fa-clock me-2"></i> Horaires
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-commandes">
            
            <div class="row g-3 align-items-end mb-4 bg-white p-3 rounded-3 shadow-sm border">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted text-uppercase">Rechercher un client</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="filter-client" class="form-control border-start-0 bg-light" placeholder="Nom ou Prénom...">
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted text-uppercase">Filtrer par statut</label>
                    <select id="filter-status" class="form-select bg-light">
                        <option value="">-- Tout afficher --</option>
                        <option value="en_attente">En attente</option>
                        <option value="validee">Validée / Acceptée</option>
                        <option value="en_preparation">En préparation</option>
                        <option value="en_cours_livraison">En livraison</option>
                        <option value="livree">Livrée</option>
                        <option value="attente_retour_materiel">⚠️ Attente retour matériel</option>
                        <option value="terminee">Terminée</option>
                        <option value="annulee">Annulée</option>
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <span class="badge bg-secondary p-2">Total : <span id="count-display"><?php echo count($commandes); ?></span></span>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Ref</th><th>Client</th><th>Date</th><th>Total</th><th>Statut</th><th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($commandes as $cmd): ?>
                                <tr class="cmd-row" 
                                    data-client="<?php echo strtolower($cmd['nom'] . ' ' . $cmd['prenom']); ?>" 
                                    data-status="<?php echo $cmd['statut']; ?>">
                                    
                                    <td class="fw-bold text-dark">#<?php echo $cmd['numero_commande']; ?></td>
                                    <td>
                                        <span class="d-block fw-bold"><?php echo htmlspecialchars($cmd['prenom'] . ' ' . $cmd['nom']); ?></span>
                                        <small class="text-muted"><?php echo htmlspecialchars($cmd['ville_livraison']); ?></small>
                                    </td>
                                    <td><?php echo (new DateTime($cmd['date_commande']))->format('d/m H:i'); ?></td>
                                    <td class="menu-price"><?php echo number_format($cmd['prix_total'], 2); ?>€</td>
                                    <td>
                                        <form onsubmit="return false;">
                                            <select class="form-select form-select-sm status-select js-update-status <?php echo $cmd['statut']; ?>" 
                                                    data-id="<?php echo $cmd['commande_id']; ?>"
                                                    data-original="<?php echo $cmd['statut']; ?>"
                                                    <?php echo ($cmd['statut'] == 'annulee') ? 'disabled' : ''; ?>>
                                                
                                                <option value="en_attente" <?php echo $cmd['statut'] == 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                                <option value="validee" <?php echo ($cmd['statut'] == 'validee' || $cmd['statut'] == 'accepte') ? 'selected' : ''; ?>>✅ Validée</option>
                                                <option value="en_preparation" <?php echo $cmd['statut'] == 'en_preparation' ? 'selected' : ''; ?>>👨‍🍳 En prépa.</option>
                                                <option value="en_cours_livraison" <?php echo $cmd['statut'] == 'en_cours_livraison' ? 'selected' : ''; ?>>🚚 En livraison</option>
                                                <option value="livree" <?php echo $cmd['statut'] == 'livree' ? 'selected' : ''; ?>>🏠 Livrée</option>
                                                <option value="attente_retour_materiel" <?php echo $cmd['statut'] == 'attente_retour_materiel' ? 'selected' : ''; ?> style="color:#d63384; font-weight:bold;">⚠️ Attente Matériel</option>
                                                <option value="terminee" <?php echo $cmd['statut'] == 'terminee' ? 'selected' : ''; ?>>🏁 Terminée</option>
                                                <option value="annulee" <?php echo $cmd['statut'] == 'annulee' ? 'selected' : ''; ?>>❌ Annulée</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <a href="index.php?page=commande_details&id=<?php echo $cmd['commande_id']; ?>" class="action-btn btn-view"><i class="fa-solid fa-eye"></i></a>
                                        
                                        <?php if($cmd['statut'] !== 'annulee' && $cmd['statut'] !== 'terminee'): ?>
                                            <button class="action-btn btn-delete js-open-cancel" 
                                                    data-id="<?php echo $cmd['commande_id']; ?>" 
                                                    data-bs-toggle="modal" data-bs-target="#cancelModal"
                                                    title="Annuler">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-menus">
            <div class="d-flex justify-content-end mb-3">
                <a href="index.php?page=admin_menu_add" class="btn-add-menu"><span>+</span> Nouveau Menu</a>
            </div>
            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Visuel</th><th>Détails</th><th>Prix</th><th>Thème</th><th class="text-end">Actions</th></tr></thead>
                        <tbody>
                            <?php foreach($menus as $menu): ?>
                                <tr>
                                    <td><img src="<?php echo !empty($menu['image_principale']) ? "assets/images/menu/" . $menu['image_principale'] : "https://via.placeholder.com/70x70"; ?>" class="menu-thumb"></td>
                                    <td>
                                        <span class="menu-title"><?php echo htmlspecialchars($menu['titre']); ?></span>
                                        <small class="text-muted d-block">Min. <?php echo $menu['nombre_personne_min']; ?> pers.</small>
                                    </td>
                                    <td><span class="menu-price"><?php echo number_format($menu['prix_par_personne'], 2); ?>€</span></td>
                                    <td><span class="badge-custom"><?php echo htmlspecialchars($menu['theme_nom'] ?? 'Aucun'); ?></span></td>
                                    <td class="text-end">
                                        <a href="index.php?page=admin_menu_edit&id=<?php echo $menu['menu_id']; ?>" class="action-btn btn-edit"><i class="fa-solid fa-pen"></i></a>
                                        <a href="index.php?page=admin_menu_delete&id=<?php echo $menu['menu_id']; ?>" class="action-btn btn-delete" onclick="return confirm('Supprimer ?');"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-avis">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3"><h5 class="mb-0 fw-bold" style="color: #8B2635;">💬 Modération des Avis</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light"><tr><th>Client</th><th>Menu</th><th>Avis</th><th>Note</th><th>Statut</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                                <?php foreach($avisList as $avis): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($avis['prenom'] . ' ' . $avis['nom']); ?></td>
                                        <td class="small text-muted"><?php echo htmlspecialchars($avis['titre']); ?></td>
                                        <td><div style="max-width: 250px;" class="text-truncate"><?php echo htmlspecialchars($avis['description']); ?></div></td>
                                        <td class="text-warning"><?php echo $avis['note']; ?> <i class="fa-solid fa-star"></i></td>
                                        <td>
                                            <?php if($avis['statut'] == 'valide'): ?><span class="badge bg-success js-badge">En ligne</span>
                                            <?php else: ?><span class="badge bg-warning text-dark js-badge">En attente</span><?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if($avis['statut'] != 'valide'): ?>
                                                <button class="btn btn-sm btn-success text-white me-1 js-validate-avis" data-id="<?php echo $avis['avis_id']; ?>"><i class="fa-solid fa-check"></i></button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-danger text-white js-delete-avis" data-id="<?php echo $avis['avis_id']; ?>"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-horaires">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3"><h5 class="mb-0 fw-bold" style="color: #8B2635;">🕒 Horaires</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light"><tr><th>Jour</th><th>Créneau</th><th class="text-end">Action</th></tr></thead>
                            <tbody>
                                <?php foreach($horaires as $h): ?>
                                    <tr>
                                        <td class="fw-bold text-uppercase" style="color:#8B2635;"><?php echo htmlspecialchars($h['jour']); ?></td>
                                        <td><input type="text" class="form-control" id="horaire-<?php echo $h['horaire_id']; ?>" value="<?php echo htmlspecialchars($h['creneau']); ?>"></td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-success text-white js-save-horaire" data-id="<?php echo $h['horaire_id']; ?>"><i class="fa-solid fa-save"></i> Enregistrer</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">Annuler la commande</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="form-cancel">
                    <input type="hidden" name="id" id="cancel-id">
                    <div class="alert alert-warning border-warning"><strong>Protocole :</strong> Contactez le client avant d'annuler.</div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Contact utilisé</label>
                        <select name="contact" class="form-select" required>
                            <option value="">-- Choix --</option><option value="GSM">GSM</option><option value="Email">Email</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Motif</label>
                        <textarea name="motif" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold">Confirmer l'annulation</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>
<script src="assets/js/employe.js?v=3"></script>