<?php
require_once __DIR__ . '/../../Models/Horaire.php';
$footerHoraires = Horaire::getAll();
?>

<footer class="text-white pt-5 pb-4" style="background-color: #8B2635; border-top: 5px solid #D8A85E;">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold" style="color: #D8A85E;">Vite & Gourmand</h5>
                <p>
                    Découvrez une cuisine authentique et savoureuse, livrée chez vous ou à emporter. 
                    Des produits frais, des recettes maison et une passion pour le goût.
                </p>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold" style="color: #D8A85E;">Liens</h5>
                <p><a href="index.php?page=home" class="text-white" style="text-decoration: none;">Accueil</a></p>
                <p><a href="index.php?page=menus" class="text-white" style="text-decoration: none;">La Carte</a></p>
                <p><a href="index.php?page=contact" class="text-white" style="text-decoration: none;">Contact</a></p>
                
                <?php if(isset($_SESSION['user'])): ?>
                    <p><a href="index.php?page=compte" class="text-white" style="text-decoration: none;">Mon Compte</a></p>
                <?php else: ?>
                    <p><a href="index.php?page=login" class="text-white" style="text-decoration: none;">Connexion</a></p>
                <?php endif; ?>
            </div>

            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold" style="color: #D8A85E;">Contact</h5>
                <p><i class="fas fa-home mr-3"></i> 12 Rue de la Gourmandise, 75000 Paris</p>
                <p><i class="fas fa-envelope mr-3"></i> contact@viteetgourmand.com</p>
                <p><i class="fas fa-phone mr-3"></i> +33 1 23 45 67 89</p>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold" style="color: #D8A85E;">Nos Horaires</h5>
                
                <ul class="list-unstyled">
                    <?php if(!empty($footerHoraires)): ?>
                        <?php foreach($footerHoraires as $fh): ?>
                            <li class="mb-2 d-flex justify-content-between">
                                <span class="text-white-50" style="width: 80px;"><?php echo htmlspecialchars($fh['jour']); ?> :</span>
                                <span class="text-white text-end flex-grow-1"><?php echo htmlspecialchars($fh['creneau']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-white-50">Horaires non disponibles</li>
                    <?php endif; ?>
                </ul>

            </div>
            
        </div>

        <hr class="mb-4" style="background-color: #D8A85E; opacity: 1;">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p>Copyright © <?php echo date('Y'); ?> Tous droits réservés par 
                    <a href="#" style="text-decoration: none;"><strong style="color: #D8A85E;">Vite & Gourmand</strong></a>
                </p>
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="text-center text-md-end">
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fab fa-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fab fa-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fab fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>