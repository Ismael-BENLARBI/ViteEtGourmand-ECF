<?php 
require_once 'Views/partials/header.php'; 
require_once 'Models/User.php'; 

$currentUser = User::getById($_SESSION['user']['id']);
?>
<link rel="stylesheet" href="assets/css/compte.css">

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Mon Espace</h1>
            <p class="text-muted">Bonjour, <?php echo htmlspecialchars($currentUser['prenom']); ?> !</p>
        </div>
        <div class="text-end">
            <?php 
                $role = $_SESSION['user']['role_id'] ?? 3;
                if ($role == 1): 
            ?>
                <a href="index.php?page=admin_dashboard" class="btn btn-dark rounded-pill px-4 me-2">
                    <i class="fa-solid fa-gauge-high"></i> Admin
                </a>
            <?php elseif ($role == 2): ?>
                <a href="index.php?page=employe_dashboard" class="btn btn-primary rounded-pill px-4 me-2">
                    <i class="fa-solid fa-briefcase"></i> Espace Employé
                </a>
            <?php endif; ?>

            <a href="index.php?page=logout" class="btn btn-outline-danger rounded-pill px-4">
                <i class="fa-solid fa-power-off"></i> Déconnexion
            </a>
        </div>
    </div>

    <ul class="nav nav-pills mb-4 gap-2" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-commandes-tab" data-bs-toggle="pill" data-bs-target="#pills-commandes" type="button">
                <i class="fa-solid fa-clock-rotate-left me-2"></i> Historique Commandes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profil-tab" data-bs-toggle="pill" data-bs-target="#pills-profil" type="button">
                <i class="fa-solid fa-user-pen me-2"></i> Mes Informations
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-commandes" role="tabpanel">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success rounded-3"><i class="fa-solid fa-check-circle me-2"></i> <?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger rounded-3"><i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <?php if (empty($commandes)): ?>
                    <div class="text-center py-5">
                        <div class="mb-3 text-muted" style="font-size: 3rem;">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </div>
                        <h4 class="text-muted">Vous n'avez pas encore passé de commande.</h4>
                        <a href="index.php?page=menus" class="btn btn-primary mt-3 rounded-pill px-4" style="background-color: #D8A85E; border:none;">
                            Découvrir nos menus
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Référence</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($commandes as $cmd): ?>
                                    <tr>
                                        <td class="fw-bold text-dark">#<?php echo htmlspecialchars($cmd['numero_commande']); ?></td>
                                        <td class="text-muted">
                                            <?php echo (new DateTime($cmd['date_commande']))->format('d/m/Y H:i'); ?>
                                        </td>
                                        <td class="fw-bold" style="color: #D8A85E;">
                                            <?php echo number_format($cmd['prix_total'], 2); ?> €
                                        </td>
                                        <td>
                                            <?php 
                                                $statusClass = 'bg-secondary';
                                                $statusText = $cmd['statut'];
                                                
                                                switch($cmd['statut']) {
                                                    case 'en_attente': $statusClass = 'bg-warning text-dark'; $statusText = 'En attente'; break;
                                                    case 'validee': $statusClass = 'bg-info text-dark'; $statusText = 'Validée'; break;
                                                    case 'en_preparation': $statusClass = 'bg-primary'; $statusText = 'En prépa.'; break;
                                                    case 'en_cours_livraison': $statusClass = 'bg-primary text-dark'; $statusText = 'En livraison'; break;
                                                    case 'livree': $statusClass = 'bg-success'; $statusText = 'Livrée'; break;
                                                    case 'terminee': $statusClass = 'bg-dark'; $statusText = 'Terminée'; break;
                                                    case 'annulee': $statusClass = 'bg-danger'; $statusText = 'Annulée'; break;
                                                    case 'attente_retour_materiel': $statusClass = 'bg-danger'; $statusText = 'Retour Matériel'; break;
                                                }
                                            ?>
                                            <span class="badge rounded-pill <?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        
                                        <td class="text-end">
                                            <a href="index.php?page=commande_details&id=<?php echo $cmd['commande_id']; ?>" class="btn btn-sm btn-outline-dark rounded-pill" title="Voir les détails">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <?php if($cmd['statut'] == 'en_attente'): ?>
                                                <a href="index.php?page=client_order_modify&id=<?php echo $cmd['commande_id']; ?>" 
                                                   class="btn btn-sm rounded-pill ms-1"
                                                   style="border-color: #8B2635; color: #8B2635;"
                                                   onmouseover="this.style.backgroundColor='#8B2635'; this.style.color='white';"
                                                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#8B2635';"
                                                   onclick="return confirm('⚠️ Modification :\nCela va annuler cette commande et remettre tous les articles dans votre panier pour que vous puissiez les modifier.\n\nContinuer ?');"
                                                   title="Modifier la commande">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>

                                                <a href="index.php?page=client_order_cancel&id=<?php echo $cmd['commande_id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger rounded-pill ms-1"
                                                   onclick="return confirm('⚠️ Êtes-vous sûr de vouloir annuler définitivement cette commande ?');"
                                                   title="Annuler la commande">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-profil" role="tabpanel">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                
                <h4 class="mb-4 fw-bold" style="color: #8B2635;">Modifier mes informations</h4>
                
                <form action="index.php?page=compte_update" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Prénom</label>
                            <input type="text" name="prenom" class="form-control" value="<?php echo htmlspecialchars($currentUser['prenom']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($currentUser['nom']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($currentUser['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" value="<?php echo htmlspecialchars($currentUser['telephone'] ?? ''); ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Adresse complète</label>
                            <input type="text" name="adresse" class="form-control" value="<?php echo htmlspecialchars($currentUser['adresse_postale'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Code Postal</label>
                            <input type="text" name="code_postal" class="form-control" value="<?php echo htmlspecialchars($currentUser['code_postal'] ?? ''); ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">Ville</label>
                            <input type="text" name="ville" class="form-control" value="<?php echo htmlspecialchars($currentUser['ville'] ?? ''); ?>">
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn text-white rounded-pill px-4" style="background-color: #8B2635;">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-5" style="border-color: #D8A85E; opacity: 0.5;">

                <h4 class="fw-bold mb-4" style="color: #8B2635;">
                    <i class="fa-solid fa-lock me-2"></i>Sécurité
                </h4>

                <?php if(isset($_GET['error']) && $_GET['error'] == 'wrong_pass'): ?>
                    <div class="alert alert-danger rounded-3"><i class="fa-solid fa-triangle-exclamation me-2"></i> L'ancien mot de passe est incorrect.</div>
                <?php elseif(isset($_GET['error']) && $_GET['error'] == 'mismatch'): ?>
                    <div class="alert alert-danger rounded-3"><i class="fa-solid fa-triangle-exclamation me-2"></i> Les nouveaux mots de passe ne correspondent pas.</div>
                <?php elseif(isset($_GET['success']) && $_GET['success'] == 'pass_updated'): ?>
                    <div class="alert alert-success rounded-3"><i class="fa-solid fa-check-circle me-2"></i> Votre mot de passe a été modifié avec succès !</div>
                <?php endif; ?>

                <form action="index.php?page=compte_update_password" method="POST" class="p-4 bg-light rounded-3 shadow-sm border">
                    <div class="row g-3 align-items-end">
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Ancien mot de passe</label>
                            <input type="password" name="old_password" class="form-control" required placeholder="••••••••">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control" required placeholder="Nouveau..." minlength="6">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Confirmer</label>
                            <input type="password" name="confirm_password" class="form-control" required placeholder="Confirmer...">
                        </div>

                        <div class="col-12 mt-3 text-end">
                            <button type="submit" class="btn text-white fw-bold px-4 rounded-pill" style="background-color: #8B2635;">
                                <i class="fa-solid fa-sync me-2"></i> Mettre à jour le mot de passe
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>

<script src="assets/js/compte.js"></script>