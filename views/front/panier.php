<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/panier.css">

<div class="container py-5">
    
    <div class="cart-container">
        <h1 class="cart-title">Votre Panier Gourmand</h1>

        <?php if(empty($panierComplet)): ?>
            <div class="text-center py-5">
                <div class="mb-3" style="font-size: 4rem;">🛒</div>
                <h3>Votre panier est vide</h3>
                <p class="text-muted">Découvrez nos menus de fête et régalez-vous !</p>
                <a href="index.php?page=menus" class="btn-checkout mt-3">Voir les menus</a>
            </div>

        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($panierComplet as $item): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <?php 
                                    $img = !empty($item['menu']['image_principale']) ? "assets/images/menu/".$item['menu']['image_principale'] : "assets/images/default.jpg";
                                ?>
                                <img src="<?php echo $img; ?>" class="cart-img" alt="Menu">
                                <div>
                                    <span class="item-title"><?php echo htmlspecialchars($item['menu']['titre']); ?></span>
                                    <small class="text-muted">Min. <?php echo $item['menu']['nombre_personne_min']; ?> pers.</small>
                                </div>
                            </div>
                        </td>

                        <td class="fw-bold"><?php echo number_format($item['menu']['prix_par_personne'], 2); ?> €</td>

                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                                x <?php echo $item['quantite']; ?>
                            </span>
                        </td>

                        <td class="fw-bold text-primary">
                            <?php echo number_format($item['total_ligne'], 2); ?> €
                        </td>

                        <td>
                            <a href="index.php?page=panier_delete&id=<?php echo $item['menu']['menu_id']; ?>" class="btn-delete" title="Supprimer">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-section">
                <div class="mb-2 text-muted text-uppercase small">Total à payer</div>
                <div class="grand-total"><?php echo number_format($totalGeneral, 2); ?> €</div>
                
                <div class="mt-4">
                    <a href="index.php?page=menus" class="text-muted me-4 text-decoration-none">Continuer mes achats</a>
                    <a href="index.php?page=commande" class="btn-checkout">Valider la commande</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once 'Views/partials/footer.php'; ?>