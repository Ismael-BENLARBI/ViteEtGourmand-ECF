<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/panier.css">

<?php
// Calculs PHP (Invisible)
$qtyTotal = 0;
if(isset($_SESSION['panier'])) {
    foreach($_SESSION['panier'] as $id => $qty) {
        $qtyTotal += $qty;
    }
}
// Formatage propre pour le JS (125.00)
$totalClean = number_format($totalPanier, 2, '.', '');
?>

<div class="container py-5">
    
    <h1 class="cart-title mb-4">Finaliser ma commande</h1>

    <form action="index.php?page=commande_validation" method="POST" 
          id="commandeForm" 
          data-subtotal="<?php echo $totalClean; ?>" 
          data-qty="<?php echo $qtyTotal; ?>">
        
        <div class="row">
            
            <div class="col-md-7">
                <div class="cart-container p-4">
                    <h4 class="mb-4 text-uppercase fw-bold">📍 Livraison</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" value="<?php echo $_SESSION['user']['prenom'] ?? ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?php echo $_SESSION['user']['nom'] ?? ''; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" placeholder="Ex: 15 rue Sainte-Catherine" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Code Postal *</label>
                            <input type="text" name="cp" id="zipCode" class="form-control" placeholder="33000" maxlength="5" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" id="cityInput" class="form-control" placeholder="Ville" required>
                        </div>
                    </div>

                    <div id="distanceInfoBox" class="alert alert-info py-2" style="display:none; font-size: 0.9rem;">
                        <i class="fa-solid fa-route"></i> Distance estimée : <strong id="distDisplay">--</strong> km
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de préstation</label>
                            <input type="date" name="date_prestation" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure de livraison</label>
                            <input type="time" name="heure_livraison" class="form-control" min="09:00" max="22:00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Instructions spéciales</label>
                        <textarea name="instructions" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="cart-container p-4" style="background-color: white;">
                    <h4 class="mb-4 text-uppercase fw-bold">🧾 Résumé</h4>
                    
                    <p class="text-muted mb-4">
                        Nombre de menus : <strong><?php echo $qtyTotal; ?></strong>
                    </p>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total</span>
                        <span class="fw-bold"><?php echo number_format($totalPanier, 2); ?> €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-success" id="discountRow" style="display: none;">
                        <span>Réduction Groupe (-10%)</span>
                        <span class="fw-bold" id="discountAmount">- 0.00 €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Frais de livraison</span>
                        <span id="shippingText" class="fw-bold">-- €</span>
                    </div>
                    <small id="shippingInfo" class="d-block mb-3 text-muted fst-italic" style="font-size: 0.8rem;">
                        Entrez votre Code Postal.
                    </small>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-4 fw-bold text-uppercase">Total</span>
                        <span class="fs-4 fw-bold" style="color: #D8A85E;" id="grandTotal">
                            <?php echo number_format($totalPanier, 2); ?> €
                        </span>
                    </div>

                    <input type="hidden" name="total_final" id="inputTotalFinal" value="<?php echo $totalClean; ?>">
                    <input type="hidden" name="frais_livraison" id="inputFrais" value="0">
                    <input type="hidden" name="montant_reduction" id="inputReduction" value="0">

                    <button type="submit" class="btn-checkout w-100 text-center">
                        Payer et Commander
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script src="assets/js/commande.js"></script>

<?php require_once 'Views/partials/footer.php'; ?>