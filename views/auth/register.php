<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/register.css">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="register-card">
                <h2 class="form-title">Créer un compte</h2>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=register_action" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="nom" class="form-control" placeholder="Votre nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="prenom" class="form-control" placeholder="Votre prénom" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe *</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••" required minlength="6">
                        <div class="form-text">6 caractères minimum.</div>
                    </div>

                    <div class="section-label">Vos coordonnées pour la livraison</div>

                    <div class="mb-3">
                        <label class="form-label">Adresse Postale</label>
                        <input type="text" name="adresse" class="form-control" placeholder="Ex: 12 rue de la Paix">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Code Postal</label>
                            <input type="text" name="cp" class="form-control" placeholder="75000">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control" placeholder="Paris">
                        </div>
                    </div>

                    <button type="submit" class="btn-register">
                        S'INSCRIRE
                    </button>
                
                    <div class="login-link">
                        Déjà un compte ? <a href="index.php?page=login">Se connecter</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>