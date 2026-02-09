<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/auth.css">

<div class="container py-5 min-vh-75 d-flex flex-column justify-content-center">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="auth-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-lock-open fa-3x mb-3" style="color: #D8A85E;"></i>
                    <h3 class="auth-title">Nouveau mot de passe</h3>
                    <p class="text-muted small">Choisissez un mot de passe sécurisé.</p>
                </div>
                
                <form action="index.php?page=reset_password_submit" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control" required minlength="6" placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Confirmation</label>
                        <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
                    </div>
                    
                    <button type="submit" class="btn btn-auth w-100 mb-3">
                        Valider le changement
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>