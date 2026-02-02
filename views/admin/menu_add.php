<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/admin_form.css">

<div class="container py-5">
    
    <a href="index.php?page=admin_dashboard" class="btn-back">
        ← Retour au tableau de bord
    </a>

    <div class="admin-form-container">
        
        <div class="form-header">
            <h1 class="form-title">Ajouter un Menu</h1>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form action="index.php?page=admin_menu_create_action" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-7">
                    <div class="mb-3">
                        <label class="form-label">Titre du menu *</label>
                        <input type="text" name="titre" class="form-control" placeholder="Ex: Menu Saveurs d'Automne" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prix / Personne (€) *</label>
                            <input type="number" step="0.01" name="prix" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Min. Personnes</label>
                            <input type="number" name="min_personnes" class="form-control" value="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Description Entrée</label>
                        <textarea name="desc_entree" class="form-control" rows="2" placeholder="Ex: Foie gras poêlé sur brioche (Allergène : Gluten...)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Description Plat</label>
                        <textarea name="desc_plat" class="form-control" rows="2" placeholder="Ex: Volaille de fête rôtie et ses légumes..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Description Dessert</label>
                        <textarea name="desc_dessert" class="form-control" rows="2" placeholder="Ex: Bûche chocolat-passion..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Accroche (Optionnel)</label>
                        <input type="text" name="description" class="form-control" placeholder="Une phrase courte pour donner envie...">
                    </div>
                </div>

                <div class="col-md-5">
                    
                    <div class="mb-4">
                        <label class="form-label">Thème</label>
                        <select name="theme_id" class="form-select">
                            <option value="">-- Choisir un thème --</option>
                            <option value="1">Noël</option>
                            <option value="2">Anniversaire</option>
                            <option value="3">Mariage</option>
                            <option value="4">Entreprise</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Régime Alimentaire</label>
                        <select name="regime_id" class="form-select">
                            <option value="">-- Aucun régime spécifique --</option>
                            <option value="1">Classique</option>
                            <option value="2">Végétarien</option>
                            <option value="3">Sans Gluten</option>
                            <option value="4">Halal</option>
                            <option value="5">Végan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Image Principale (Couverture) *</label>
                        <div class="image-upload-zone" onclick="document.getElementById('img_main').click();">
                            <div id="text_main">
                                📷<br>Cliquez pour la couverture
                            </div>
                            <input type="file" id="img_main" name="image_principale" class="d-none" onchange="previewImage(this, 'preview_main', 'text_main')">
                            <img id="preview_main" class="image-preview">
                        </div>
                    </div>

                    <hr>

                    <label class="form-label">Détails du Menu</label>
                    <div class="upload-grid">
                        
                        <div class="mini-upload-zone" onclick="document.getElementById('img_entree').click();">
                            <div id="text_entree"><small>ENTRÉE</small>➕</div>
                            <input type="file" id="img_entree" name="image_entree" class="d-none" onchange="previewImage(this, 'preview_entree', 'text_entree')">
                            <img id="preview_entree" class="mini-preview">
                        </div>

                        <div class="mini-upload-zone" onclick="document.getElementById('img_plat').click();">
                            <div id="text_plat"><small>PLAT</small>➕</div>
                            <input type="file" id="img_plat" name="image_plat" class="d-none" onchange="previewImage(this, 'preview_plat', 'text_plat')">
                            <img id="preview_plat" class="mini-preview">
                        </div>

                        <div class="mini-upload-zone" onclick="document.getElementById('img_dessert').click();">
                            <div id="text_dessert"><small>DESSERT</small>➕</div>
                            <input type="file" id="img_dessert" name="image_dessert" class="d-none" onchange="previewImage(this, 'preview_dessert', 'text_dessert')">
                            <img id="preview_dessert" class="mini-preview">
                        </div>

                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">Enregistrer le menu</button>

        </form>
    </div>
</div>

<script>
// Fonction générique qui marche pour les 4 images
function previewImage(input, previewId, textId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            // On affiche l'image
            var img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
            
            // On cache le texte
            document.getElementById(textId).style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once 'Views/partials/footer.php'; ?>