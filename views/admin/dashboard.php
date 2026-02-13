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
        <li class="nav-item">
            <button class="nav-link active fw-bold px-4 py-2" id="pills-commandes-tab" data-bs-toggle="pill" data-bs-target="#pills-commandes" type="button">
                📦 Commandes
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-2" id="pills-menus-tab" data-bs-toggle="pill" data-bs-target="#pills-menus" type="button">
                🍽️ Menus
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-avis-tab" data-bs-toggle="pill" data-bs-target="#pills-avis" type="button" role="tab">
                <i class="fa-solid fa-star me-2"></i> Avis
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-users-tab" data-bs-toggle="pill" data-bs-target="#pills-users" type="button" role="tab">
                <i class="fa-solid fa-users me-2"></i> Utilisateurs
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-messages-tab" data-bs-toggle="pill" data-bs-target="#pills-messages" type="button" role="tab">
                <i class="fa-solid fa-envelope me-2"></i> Messagerie
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-stats-tab" data-bs-toggle="pill" data-bs-target="#pills-stats" type="button" role="tab">
                <i class="fa-solid fa-chart-line me-2"></i> Statistiques
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-horaires-tab" data-bs-toggle="pill" data-bs-target="#pills-horaires" type="button" role="tab">
                <i class="fa-solid fa-clock me-2"></i> Horaires
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-commandes" role="tabpanel">
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
                            <?php if(empty($commandes)): ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted">Aucune commande.</td></tr>
                            <?php else: ?>
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
                                                <select class="form-select form-select-sm status-select js-status-select <?php echo $cmd['statut']; ?>" data-id="<?php echo $cmd['commande_id']; ?>">
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
                                            <a href="index.php?page=admin_commande_delete&id=<?php echo $cmd['commande_id']; ?>" class="action-btn btn-delete" onclick="return confirm('Supprimer ?');"><i class="fa-solid fa-trash"></i></a>
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
                <a href="index.php?page=admin_menu_add" class="btn-add-menu"><span>+</span> Nouveau Menu</a>
            </div>
            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Visuel</th><th>Détails</th><th>Prix</th><th>Thème</th><th class="text-end">Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php if(empty($menus)): ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted">Aucun menu.</td></tr>
                            <?php else: ?>
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
                                    <th>Date</th><th>Client</th><th>Menu</th><th style="width: 30%;">Commentaire</th><th>Note</th><th>Statut</th><th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($avisList)): ?>
                                    <tr><td colspan="7" class="text-center py-4">Aucun avis.</td></tr>
                                <?php else: ?>
                                    <?php foreach($avisList as $avis): ?>
                                        <tr>
                                            <td><?php echo (new DateTime($avis['date_publication']))->format('d/m/Y'); ?></td>
                                            <td class="fw-bold"><?php echo htmlspecialchars($avis['prenom'] . ' ' . $avis['nom']); ?></td>
                                            <td class="text-muted small"><?php echo htmlspecialchars($avis['titre']); ?></td>
                                            <td>
                                                <div style="text-align: left !important; min-width: 350px;">
                                                    <?php echo htmlspecialchars($avis['description']); ?>
                                                </div>
                                            </td>
                                            <td class="text-warning" style="white-space: nowrap;">
                                                <?php echo $avis['note']; ?> <i class="fa-solid fa-star text-small"></i>
                                            </td>
                                            <td>
                                                <?php if($avis['statut'] == 'valide'): ?><span class="badge bg-success js-badge">En ligne</span><?php else: ?><span class="badge bg-warning text-dark js-badge">En attente</span><?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if($avis['statut'] != 'valide'): ?>
                                                    <button class="btn btn-sm btn-success text-white me-1 js-validate-avis" data-id="<?php echo $avis['avis_id']; ?>"><i class="fa-solid fa-check"></i></button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-danger text-white js-delete-avis" data-id="<?php echo $avis['avis_id']; ?>"><i class="fa-solid fa-trash"></i></button>
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

        <div class="tab-pane fade" id="pills-users" role="tabpanel">
            
            <div class="d-flex justify-content-end mb-3">
                <button class="btn-add-menu" data-bs-toggle="modal" data-bs-target="#modalCreateUser" style="border:none;">
                    <span>+</span> Nouvel Utilisateur
                </button>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold" style="color: #8B2635;">👥 Gestion des Utilisateurs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr><th>ID</th><th>Nom / Prénom</th><th>Email</th><th>Rôle</th><th class="text-end">Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php if(empty($users)): ?>
                                    <tr><td colspan="5" class="text-center py-4">Aucun utilisateur.</td></tr>
                                <?php else: ?>
                                    <?php foreach($users as $u): ?>
                                        <tr>
                                            <td class="text-muted">#<?php echo $u['utilisateur_id']; ?></td>
                                            <td class="fw-bold">
                                                <?php echo htmlspecialchars($u['nom'] . ' ' . $u['prenom']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                                            <td>
                                                <?php 
                                                if($u['utilisateur_id'] == $_SESSION['user']['id']): ?>
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="fa-solid fa-user-shield"></i> Admin (Vous)
                                                    </span>

                                                <?php 
                                                elseif($u['role_id'] == 1): ?>
                                                    <span class="badge bg-danger">Administrateur</span>

                                                <?php 
                                                else: ?>
                                                    <form onsubmit="return false;">
                                                        <select class="form-select form-select-sm js-role-select" 
                                                                data-id="<?php echo $u['utilisateur_id']; ?>"
                                                                style="width: 140px; font-weight:bold; color: <?php echo ($u['role_id'] == 2 ? '#0d6efd' : '#333'); ?>">
                                                            <option value="3" <?php echo $u['role_id'] == 3 ? 'selected' : ''; ?>>Client</option>
                                                            <option value="2" <?php echo $u['role_id'] == 2 ? 'selected' : ''; ?>>Employé</option>
                                                            </select>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <?php 
                                                if($u['utilisateur_id'] != $_SESSION['user']['id'] && $u['role_id'] != 1): ?>
                                                    <button class="btn btn-sm btn-danger text-white js-delete-user" data-id="<?php echo $u['utilisateur_id']; ?>"><i class="fa-solid fa-trash"></i></button>
                                                <?php endif; ?>
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

        <div class="tab-pane fade" id="pills-messages" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold" style="color: #8B2635;">📩 Messages Reçus</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr><th>Date</th><th>Auteur</th><th>Sujet</th><th>Message</th><th class="text-end">Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php if(empty($messages)): ?>
                                    <tr><td colspan="5" class="text-center py-4">Aucun message.</td></tr>
                                <?php else: ?>
                                    <?php foreach($messages as $msg): ?>
                                        <tr class="<?php echo $msg['est_traite'] == 0 ? 'fw-bold bg-light' : ''; ?> js-message-row">
                                            <td><?php echo (new DateTime($msg['date_envoi']))->format('d/m H:i'); ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($msg['nom_visiteur'] ?? 'Inconnu'); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($msg['email_visiteur']); ?></small>
                                            </td>
                                            <td style="color: #8B2635;"><?php echo htmlspecialchars($msg['sujet']); ?></td>
                                            <td>
                                                <div style="max-width: 300px; cursor: help;" title="<?php echo htmlspecialchars($msg['contenu_message']); ?>">
                                                    <span class="d-block text-truncate"><?php echo htmlspecialchars($msg['contenu_message']); ?></span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-dark me-1 js-open-reply-modal" 
                                                        data-id="<?php echo $msg['message_id']; ?>"
                                                        data-nom="<?php echo htmlspecialchars($msg['nom_visiteur'] ?? 'Client'); ?>"
                                                        data-email="<?php echo htmlspecialchars($msg['email_visiteur']); ?>"
                                                        data-sujet="<?php echo htmlspecialchars($msg['sujet']); ?>"
                                                        data-message="<?php echo htmlspecialchars($msg['contenu_message']); ?>"
                                                        data-reponse="<?php echo htmlspecialchars($msg['reponse'] ?? ''); ?>"
                                                        title="Lire et Répondre">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger text-white js-delete-message" data-id="<?php echo $msg['message_id']; ?>"><i class="fa-solid fa-trash"></i></button>
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

        <div class="tab-pane fade" id="pills-stats" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                
                <div class="row g-3 align-items-end mb-4 bg-light p-3 rounded-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase">Date de début</label>
                        <input type="date" id="stats-start" class="form-control" value="<?php echo date('Y-m-01'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase">Date de fin</label>
                        <input type="date" id="stats-end" class="form-control" value="<?php echo date('Y-m-t'); ?>">
                    </div>
                    <div class="col-md-4">
                        <button id="js-filter-stats" class="btn btn-primary w-100 fw-bold text-white" style="background-color:#8B2635; border:none;">
                            <i class="fa-solid fa-filter me-2"></i> Appliquer le filtre
                        </button>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #8B2635 0%, #681c27 100%); color: white;">
                            <div class="card-body p-4">
                                <h6 class="text-white-50 text-uppercase mb-2 small fw-bold">Chiffre d'Affaires (Période)</h6>
                                <h2 class="mb-0 fw-bold display-5" id="stats-ca">-- €</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="background-color: #fdf5e6;">
                            <div class="card-body p-4">
                                <h6 class="text-muted text-uppercase mb-2 small fw-bold">Top Vente (Période)</h6>
                                <h3 class="mb-1 fw-bold text-dark" id="stats-best-titre">--</h3>
                                <div class="text-success fw-bold" id="stats-best-count">-- ventes</div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-3" style="color: #8B2635;">📊 Historique des Commandes (12 derniers mois)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr><th>Mois</th><th>Nombre de Commandes</th><th>Barre visuelle</th></tr>
                        </thead>
                        <tbody id="stats-history-body">
                            </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="tab-pane fade" id="pills-horaires" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold" style="color: #8B2635;">🕒 Horaires (Pied de page)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 20%;">Jour</th>
                                    <th>Créneaux d'ouverture (Texte libre)</th>
                                    <th class="text-end" style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($horaires)): ?>
                                    <?php foreach($horaires as $h): ?>
                                        <tr>
                                            <td class="fw-bold text-uppercase" style="color:#8B2635;">
                                                <?php echo htmlspecialchars($h['jour']); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="horaire-<?php echo $h['horaire_id']; ?>" 
                                                       value="<?php echo htmlspecialchars($h['creneau']); ?>" 
                                                       placeholder="Ex: 12h-14h ou Fermé">
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-success text-white js-save-horaire" 
                                                        data-id="<?php echo $h['horaire_id']; ?>">
                                                    <i class="fa-solid fa-save me-1"></i> Enregistrer
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center">Aucun horaire trouvé. Veuillez vérifier la base de données.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #8B2635;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-envelope-open-text me-2"></i> Gestion du Message</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <div class="alert alert-light border mb-4">
                    <div class="row">
                        <div class="col-md-6"><strong>De :</strong> <span id="modal-nom"></span> (<span id="modal-email"></span>)</div>
                        <div class="col-md-6 text-end text-muted small">Sujet : <span id="modal-sujet" class="fw-bold"></span></div>
                    </div>
                </div>

                <h6 class="fw-bold text-muted text-uppercase small">Message du client :</h6>
                <div class="p-3 bg-light rounded-3 mb-4" style="border-left: 4px solid #D8A85E;">
                    <p id="modal-message" class="mb-0" style="white-space: pre-wrap;"></p>
                </div>

                <div id="reply-section">
                    <h6 class="fw-bold text-muted text-uppercase small">Votre Réponse :</h6>
                    <form id="form-reply">
                        <input type="hidden" id="reply-id">
                        <input type="hidden" id="reply-email">
                        <textarea class="form-control mb-3" id="reply-content" rows="5" placeholder="Écrivez votre réponse ici... Elle sera envoyée par email au client."></textarea>
                        <div class="text-end">
                            <button type="submit" class="btn text-white fw-bold px-4 rounded-pill" style="background-color: #8B2635;">
                                <i class="fa-solid fa-paper-plane me-2"></i> Envoyer la réponse
                            </button>
                        </div>
                    </form>
                </div>

                <div id="already-replied" class="d-none">
                    <h6 class="fw-bold text-success text-uppercase small"><i class="fa-solid fa-check me-1"></i> Réponse envoyée :</h6>
                    <div class="p-3 bg-success bg-opacity-10 border border-success rounded-3 text-success">
                        <p id="modal-reponse-content" class="mb-0"></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #8B2635;">
                <h5 class="modal-title">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-user">
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label small">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label small">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Rôle</label>
                        <select name="role" class="form-select" required>
                            <option value="3" selected>Client</option>
                            <option value="2">Employé</option>
                            </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold">Créer le compte</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>

<script src="assets/js/admin_dashboard.js?v=4"></script>