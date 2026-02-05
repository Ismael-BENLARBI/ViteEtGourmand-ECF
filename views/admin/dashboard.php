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
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-avis-tab" data-bs-toggle="pill" data-bs-target="#pills-avis" type="button" role="tab">
                <i class="fa-solid fa-star me-2"></i> Avis Clients
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
                                            <form onsubmit="return false;">
                                                <select class="form-select form-select-sm status-select js-status-select <?php echo $cmd['statut']; ?>" 
                                                        data-id="<?php echo $cmd['commande_id']; ?>">
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

                                            <a href="index.php?page=admin_commande_delete&id=<?php echo $cmd['commande_id']; ?>" 
                                               class="action-btn btn-delete" 
                                               title="Supprimer la commande"
                                               onclick="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette commande ? Cette action est définitive.');">
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
        
        <div class="tab-pane fade" id="pills-avis" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold" style="color: #8B2635;">💬 Modération des Avis</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Menu</th>
                                    <th>Note</th>
                                    <th>Commentaire</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($avisList)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Aucun avis pour le moment.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($avisList as $avis): ?>
                                        <tr>
                                            <td><?php echo (new DateTime($avis['date_publication']))->format('d/m/Y'); ?></td>
                                            <td class="fw-bold"><?php echo htmlspecialchars($avis['prenom'] . ' ' . $avis['nom']); ?></td>
                                            <td class="text-muted small"><?php echo htmlspecialchars($avis['titre']); ?></td>
                                            <td class="text-warning">
                                                <?php echo $avis['note']; ?> <i class="fa-solid fa-star text-small"></i>
                                            </td>
                                            <td>
                                                <small class="fst-italic">"<?php echo htmlspecialchars($avis['description']); ?>"</small>
                                            </td>
                                            <td>
                                                <?php if($avis['statut'] == 'valide'): ?>
                                                    <span class="badge bg-success js-badge">En ligne</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark js-badge">En attente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if($avis['statut'] != 'valide'): ?>
                                                    <button class="btn btn-sm btn-success text-white me-1 js-validate-avis" 
                                                            data-id="<?php echo $avis['avis_id']; ?>"
                                                            title="Valider et publier">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                <?php endif; ?>

                                                <button class="btn btn-sm btn-danger text-white js-delete-avis" 
                                                        data-id="<?php echo $avis['avis_id']; ?>"
                                                        title="Supprimer définitivement">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. GESTION DU STATUT COMMANDE (Sans rechargement)
    const statusSelects = document.querySelectorAll('.js-status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const commandeId = this.getAttribute('data-id');
            const newStatus = this.value;
            
            // Données à envoyer
            const formData = new FormData();
            formData.append('id', commandeId);
            formData.append('statut', newStatus);
            formData.append('ajax', '1');

            fetch('index.php?page=admin_commande_status', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Feedback visuel (bordure verte temporaire)
                    this.style.borderColor = '#198754';
                    this.style.boxShadow = '0 0 0 0.25rem rgba(25, 135, 84, 0.25)';
                    setTimeout(() => { 
                        this.style.borderColor = ''; 
                        this.style.boxShadow = '';
                    }, 1000);
                    
                    // Mise à jour de la classe CSS pour la couleur
                    this.className = `form-select form-select-sm status-select js-status-select ${newStatus}`;
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    });

    // 2. VALIDATION AVIS (Sans rechargement)
    document.querySelectorAll('.js-validate-avis').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const avisId = this.getAttribute('data-id');
            const row = this.closest('tr');
            const badge = row.querySelector('.js-badge');

            fetch(`index.php?page=admin_avis_validate&id=${avisId}&ajax=1`)
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Mise à jour visuelle du badge
                    badge.className = 'badge bg-success js-badge';
                    badge.textContent = 'En ligne';
                    // Suppression du bouton valider
                    this.remove(); 
                }
            });
        });
    });

    // 3. SUPPRESSION AVIS (Sans rechargement)
    document.querySelectorAll('.js-delete-avis').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(!confirm('Voulez-vous vraiment supprimer cet avis ?')) return;

            const avisId = this.getAttribute('data-id');
            const row = this.closest('tr');

            fetch(`index.php?page=admin_avis_delete&id=${avisId}&ajax=1`)
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Animation de disparition
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(() => row.remove(), 500);
                }
            });
        });
    });

});
</script>

<?php require_once 'Views/partials/footer.php'; ?>