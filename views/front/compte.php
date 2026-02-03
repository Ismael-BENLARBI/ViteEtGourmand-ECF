<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/compte.css">

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold">Mon Espace</h1>
            <p class="text-muted">Bonjour, <?php echo htmlspecialchars($_SESSION['user']['prenom']); ?> !</p>
        </div>
        <a href="index.php?page=logout" class="btn btn-outline-danger rounded-pill px-4">
            <i class="fa-solid fa-power-off"></i> Déconnexion
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h3 class="mb-4 fw-bold text-uppercase" style="font-size: 1.2rem; letter-spacing: 1px;">
            <i class="fa-solid fa-clock-rotate-left me-2"></i> Historique de commandes
        </h3>

        <?php if (empty($mesCommandes)): ?>
            
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
                            <th class="text-end">Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($mesCommandes as $cmd): ?>
                            <tr>
                                <td class="fw-bold text-dark">
                                    #<?php echo htmlspecialchars($cmd['numero_commande']); ?>
                                </td>
                                
                                <td class="text-muted">
                                    <?php 
                                        $date = new DateTime($cmd['date_commande']);
                                        echo $date->format('d/m/Y'); 
                                    ?>
                                </td>
                                
                                <td class="fw-bold" style="color: #D8A85E;">
                                    <?php echo number_format($cmd['prix_total'], 2); ?> €
                                </td>
                                
                                <td>
                                    <?php 
                                        // Gestion des couleurs de statut
                                        $statusClass = 'bg-secondary';
                                        $statusText = $cmd['statut'];

                                        if($cmd['statut'] == 'en_attente') {
                                            $statusClass = 'bg-warning text-dark';
                                            $statusText = 'En attente';
                                        } elseif($cmd['statut'] == 'validee') {
                                            $statusClass = 'bg-info text-dark';
                                            $statusText = 'Validée';
                                        } elseif($cmd['statut'] == 'livree') {
                                            $statusClass = 'bg-success';
                                            $statusText = 'Livrée';
                                        }
                                    ?>
                                    <span class="badge rounded-pill <?php echo $statusClass; ?> px-3 py-2">
                                        <?php echo ucfirst($statusText); ?>
                                    </span>
                                </td>
                                
                                <td class="text-end">
                                    <a href="index.php?page=commande_details&id=<?php echo $cmd['commande_id']; ?>" class="btn btn-sm btn-outline-dark rounded-pill">
                                        Voir 
                                        <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>