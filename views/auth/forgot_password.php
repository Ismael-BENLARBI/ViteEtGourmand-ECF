<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/auth.css">

<div class="container py-5 min-vh-75 d-flex flex-column justify-content-center">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="auth-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-key fa-3x mb-3" style="color: #D8A85E;"></i>
                    <h3 class="auth-title">Récupération</h3>
                    <p class="text-muted small">
                        Entrez votre email pour recevoir un lien de réinitialisation sécurisé.
                    </p>
                </div>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-custom alert-custom-danger p-3 mb-4">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> Email introuvable.
                    </div>
                <?php elseif(isset($_GET['success'])): ?>
                    <div class="alert alert-custom alert-custom-success p-3 mb-4">
                        <i class="fa-solid fa-paper-plane me-2"></i> Email envoyé ! Vérifiez vos spams (ou log_emails.txt en local).
                    </div>
                <?php endif; ?>

                <form action="index.php?page=forgot_password_submit" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Votre Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                                <i class="fa-solid fa-envelope text-muted"></i>
                            </span>
                            <input type="email" name="email" class="form-control border-start-0" required placeholder="exemple@email.com" style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-auth w-100 mb-3">
                        Envoyer le lien
                    </button>
                </form>

                <div class="text-center mt-2">
                    <a href="index.php?page=login" class="auth-link">
                        <i class="fa-solid fa-arrow-left me-1"></i> Retour à la connexion
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>