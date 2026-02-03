<?php require_once 'Views/partials/header.php'; ?>
<link rel="stylesheet" href="assets/css/admin_form.css">

<div class="container py-5">
    <a href="index.php?page=admin_dashboard" class="btn-back">← Retour</a>

    <div class="admin-form-container">
        <div class="form-header">
            <h1 class="form-title">Modifier le Menu</h1>
        </div>

        <form action="index.php?page=admin_menu_edit_action&id=<?php echo $menu['menu_id']; ?>" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-7">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control" value="<?php echo htmlspecialchars($menu['titre']); ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prix (€)</label>
                            <input type="number" step="0.01" name="prix" class="form-control" value="<?php echo $menu['prix_par_personne']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Min. Personnes</label>
                            <input type="number" name="min_personnes" class="form-control" value="<?php echo $menu['nombre_personne_min']; ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-uppercase small text-muted">Accroche</label>
                        <textarea name="description" class="form-control" rows="2"><?php echo htmlspecialchars($menu['description']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-uppercase small text-muted">Entrée</label>
                        <textarea name="desc_entree" class="form-control" rows="2"><?php echo htmlspecialchars($menu['description_entree']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-uppercase small text-muted">Plat</label>
                        <textarea name="desc_plat" class="form-control" rows="2"><?php echo htmlspecialchars($menu['description_plat']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-uppercase small text-muted">Dessert</label>
                        <textarea name="desc_dessert" class="form-control" rows="2"><?php echo htmlspecialchars($menu['description_dessert']); ?></textarea>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label">Thème</label>
                        <select name="theme_id" class="form-select">
                            <option value="">-- Choisir --</option>
                            <?php foreach($themes as $t): ?>
                                <option value="<?php echo $t['theme_id']; ?>" <?php if($menu['theme_id'] == $t['theme_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($t['libelle']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Régime</label>
                        <select name="regime_id" class="form-select">
                            <option value="">-- Aucun --</option>
                            <?php foreach($regimes as $r): ?>
                                <option value="<?php echo $r['regime_id']; ?>" <?php if($menu['regime_id'] == $r['regime_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($r['libelle']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Image Principale</label>
                        <div class="image-upload-zone p-2">
                            <input type="hidden" name="old_img_principale" value="<?php echo $menu['image_principale']; ?>">
                            
                            <?php if($menu['image_principale']): ?>
                                <img src="assets/images/menu/<?php echo $menu['image_principale']; ?>" style="max-height:100px; display:block; margin:0 auto 10px;">
                            <?php endif; ?>
                            <input type="file" name="image_principale" class="form-control">
                        </div>
                    </div>

                    <label class="form-label">Modifier les plats</label>
                    <div class="upload-grid">
                        <div class="mini-upload-zone" style="height:auto;">
                            <input type="hidden" name="old_img_entree" value="<?php echo $menu['image_entree']; ?>">
                            <small>ENTRÉE</small>
                            <input type="file" name="image_entree" class="form-control form-control-sm mt-2">
                        </div>
                        <div class="mini-upload-zone" style="height:auto;">
                            <input type="hidden" name="old_img_plat" value="<?php echo $menu['image_plat']; ?>">
                            <small>PLAT</small>
                            <input type="file" name="image_plat" class="form-control form-control-sm mt-2">
                        </div>
                        <div class="mini-upload-zone" style="height:auto;">
                            <input type="hidden" name="old_img_dessert" value="<?php echo $menu['image_dessert']; ?>">
                            <small>DESSERT</small>
                            <input type="file" name="image_dessert" class="form-control form-control-sm mt-2">
                        </div>
                    </div>

                </div>
            </div>

            <button type="submit" class="btn-submit">Mettre à jour</button>
        </form>
    </div>
</div>
<?php require_once 'Views/partials/footer.php'; ?>