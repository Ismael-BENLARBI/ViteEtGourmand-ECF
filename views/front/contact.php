<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/contact.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9"> <div class="contact-card">
                
                <div class="contact-header">
                    <h1 class="contact-title">Contactez-nous</h1>
                    <div class="separator"></div>
                    <p class="contact-subtitle">Une envie particulière ? Une question sur nos menus ?</p>
                </div>

                <div class="card-body">
                    
                    <?php if(isset($_GET['success'])): ?>
                        <div class="alert alert-success rounded-3 mb-4 py-3 shadow-sm">
                            <i class="fa-solid fa-circle-check me-2"></i> <strong>Merci !</strong> Votre message a bien été envoyé.
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=contact_submit" method="POST">
                        <div class="row g-4"> <div class="col-md-6">
                                <label class="form-label">Votre Nom</label>
                                <input type="text" name="nom" class="form-control" placeholder="ex: Jean Dupont" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Votre Email</label>
                                <input type="email" name="email" class="form-control" placeholder="ex: jean@email.com" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Sujet de la demande</label>
                                <input type="text" name="sujet" class="form-control" placeholder="ex: Privatisation, Allergies..." required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Votre Message</label>
                                <textarea name="contenu" class="form-control" rows="6" placeholder="Dites-nous tout..." required></textarea>
                            </div>

                            <div class="col-12 btn-submit-wrapper">
                                <button type="submit" class="btn btn-send">
                                    <span>Envoyer le message</span>
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4 text-muted small">
                <p><i class="fa-solid fa-clock me-1" style="color:#D8A85E;"></i> Réponse sous 24h ouvrées</p>
            </div>

        </div>
    </div>
</div>

<?php require_once 'Views/partials/footer.php'; ?>