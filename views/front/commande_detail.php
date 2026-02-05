<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/details.css">

<div class="container py-5">

    <?php 
        // Gestion du bouton retour
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $lienRetour = 'index.php?page=admin_dashboard';
            $texteRetour = 'Retour au Dashboard';
        } else {
            $lienRetour = 'index.php?page=compte';
            $texteRetour = 'Retour à mes commandes';
        }
    ?>

    <a href="<?php echo $lienRetour; ?>" class="btn btn-link text-decoration-none mb-4" style="color: #8B2635;">
        <i class="fa-solid fa-arrow-left"></i> <?php echo $texteRetour; ?>
    </a>
    
    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="detail-card p-4 h-100">
                <h4 class="mb-4 text-uppercase fw-bold">📦 Infos Livraison</h4>
                
                <div class="mb-3">
                    <label class="text-muted small">Référence</label>
                    <div class="fw-bold">#<?php echo htmlspecialchars($commande['numero_commande']); ?></div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Date de commande</label>
                    <div><?php echo (new DateTime($commande['date_commande']))->format('d/m/Y à H:i'); ?></div>
                </div>

                <hr style="border-color: #D8A85E;">

                <div class="mb-3">
                    <label class="text-muted small">Adresse de livraison</label>
                    <div class="fw-bold">
                        <?php echo htmlspecialchars($commande['adresse_livraison']); ?><br>
                        <?php echo htmlspecialchars($commande['code_postal_livraison'] . ' ' . $commande['ville_livraison']); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Téléphone</label>
                    <div><?php echo htmlspecialchars($commande['telephone']); ?></div>
                </div>

                <?php if(!empty($commande['instructions'])): ?>
                <div class="alert mt-3" style="background: #fff3cd; color: #856404; font-size: 0.9rem;">
                    <strong>Note :</strong> <?php echo nl2br(htmlspecialchars($commande['instructions'])); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8">
            <div class="detail-card p-4">
                <h4 class="mb-4 text-uppercase fw-bold">🍽️ Votre Menu</h4>

                <div class="list-group list-group-flush mb-4">
                    <?php foreach($details as $item): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-bottom-0" style="border-bottom: 1px solid #eee !important;">
                            <div class="d-flex align-items-center gap-3">
                                <img src="assets/images/menu/<?php echo $item['image_principale']; ?>" 
                                alt="Menu" 
                                class="rounded-circle" 
                                style="width: 50px; height: 50px; object-fit: cover;">
                                
                                <div>
                                    <div class="fw-bold" style="color: #333;"><?php echo htmlspecialchars($item['titre']); ?></div>
                                    <small class="text-muted">Prix unitaire : <?php echo number_format($item['prix_unitaire'], 2); ?> €</small>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <span class="badge rounded-pill bg-light text-dark border">x <?php echo $item['quantite']; ?></span>
                                <div class="fw-bold mt-1" style="color: #D8A85E;">
                                    <?php echo number_format($item['prix_unitaire'] * $item['quantite'], 2); ?> €
                                </div>
                            </div>

                            <?php if($commande['statut'] == 'livree' && $_SESSION['user']['role'] !== 'admin'): ?>
                                <div class="ms-3">
                                    <button type="button" 
                                            class="btn btn-sm btn-rate" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalAvisMenu<?php echo $item['menu_id']; ?>">
                                        <i class="fa-regular fa-star"></i> Noter
                                    </button>
                                </div>

                                <div class="modal fade" id="modalAvisMenu<?php echo $item['menu_id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title modal-title-bordeaux">Noter : <?php echo htmlspecialchars($item['titre']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="index.php?page=avis_add" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="menu_id" value="<?php echo $item['menu_id']; ?>">
                                                    
                                                    <div class="mb-3 text-center">
                                                        <label class="form-label fw-bold">Note / 5</label>
                                                        <select name="note" class="form-select text-center select-rate">
                                                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                                            <option value="4">⭐⭐⭐⭐ Très bon</option>
                                                            <option value="3">⭐⭐⭐ Bon</option>
                                                            <option value="2">⭐⭐ Moyen</option>
                                                            <option value="1">⭐ Mauvais</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Votre avis</label>
                                                        <textarea name="description" class="form-control" rows="3" placeholder="C'était bon ?" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-submit-avis w-100">Publier l'avis</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="bg-light p-3 rounded-3">
                    <?php 
                        $sousTotal = $commande['prix_total'] - $commande['prix_livraison'] + $commande['montant_reduction'];
                    ?>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total</span>
                        <span><?php echo number_format($sousTotal, 2); ?> €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Livraison</span>
                        <span>+ <?php echo number_format($commande['prix_livraison'], 2); ?> €</span>
                    </div>

                    <?php if($commande['montant_reduction'] > 0): ?>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Réduction Groupe</span>
                        <span>- <?php echo number_format($commande['montant_reduction'], 2); ?> €</span>
                    </div>
                    <?php endif; ?>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-uppercase">Total Payé</span>
                        <span class="fs-4 fw-bold" style="color: #8B2635;">
                            <?php echo number_format($commande['prix_total'], 2); ?> €
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>