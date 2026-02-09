<?php require_once 'Views/partials/header.php'; ?>

<link rel="stylesheet" href="assets/css/login.css">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5"> <div class="login-card">
                <h2 class="login-title">Connexion</h2>

                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success text-center">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=login_action" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••" required>
                    </div>

                    <div class="text-end mb-3">
                        <a href="index.php?page=forgot_password" class="small text-muted" style="text-decoration: none;">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <button type="submit" class="btn-login">
                        SE CONNECTER
                    </button>
                    
                    <div class="register-link">
                        Pas encore de compte ? <a href="index.php?page=register">S'inscrire</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>