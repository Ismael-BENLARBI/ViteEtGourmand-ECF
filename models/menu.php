<?php
class Menu {
    public static function getAll($filters = []) {
        global $pdo;
        $sql = "SELECT m.*, t.libelle as theme_nom, r.libelle as regime_nom 
                FROM menu m
                LEFT JOIN theme t ON m.theme_id = t.theme_id
                LEFT JOIN regime r ON m.regime_id = r.regime_id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['theme']) && $filters['theme'] !== 'all') {
            $sql .= " AND m.theme_id = :theme";
            $params[':theme'] = $filters['theme'];
        }

        if (!empty($filters['regime']) && $filters['regime'] !== 'all') {
            $sql .= " AND r.libelle = :regime";
            $params[':regime'] = $filters['regime'];
        }

        if (!empty($filters['prix'])) {
            $sql .= " AND m.prix_par_personne <= :prix";
            $params[':prix'] = $filters['prix'];
        }

        if (!empty($filters['pers'])) {
            $sql .= " AND m.nombre_personne_min <= :pers";
            $params[':pers'] = $filters['pers'];
        }

        $sql .= " ORDER BY m.titre ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getThemes() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM theme ORDER BY libelle ASC");
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        global $pdo;
        $sql = "SELECT m.*, 
                       t.libelle AS theme_nom, 
                       r.libelle AS regime_nom 
                FROM menu m
                LEFT JOIN theme t ON m.theme_id = t.theme_id
                LEFT JOIN regime r ON m.regime_id = r.regime_id
                WHERE m.menu_id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public static function create($titre, $desc, $descE, $descP, $descD, $prix, $min, $theme, $regime, $img1, $img2, $img3, $img4, $stock = 50, $conditions = null) {
        global $pdo;
        $sql = "INSERT INTO menu (
                    titre, description, description_entree, description_plat, description_dessert, 
                    prix_par_personne, nombre_personne_min, theme_id, regime_id, 
                    image_principale, image_entree, image_plat, image_dessert, quantite_restante, conditions
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $titre, $desc, $descE, $descP, $descD, 
            $prix, $min, $theme, $regime, 
            $img1, $img2, $img3, $img4, $stock, $conditions
        ]);
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM menu WHERE menu_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function update($id, $titre, $desc, $descE, $descP, $descD, $prix, $min, $theme, $regime, $img1, $img2, $img3, $img4, $stock, $conditions) {
        global $pdo;
        $sql = "UPDATE menu SET 
                titre = :titre, 
                description = :desc, 
                description_entree = :descE,
                description_plat = :descP,
                description_dessert = :descD,
                prix_par_personne = :prix, 
                nombre_personne_min = :min,
                quantite_restante = :stock, 
                conditions = :conditions,
                theme_id = :theme, 
                regime_id = :regime,
                image_principale = :img1,
                image_entree = :img2,
                image_plat = :img3,
                image_dessert = :img4
                WHERE menu_id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titre' => $titre,
            ':desc' => $desc,
            ':descE' => $descE,
            ':descP' => $descP,
            ':descD' => $descD,
            ':prix' => $prix,
            ':min' => $min,
            ':stock' => $stock,
            ':conditions' => $conditions,
            ':theme' => $theme,
            ':regime' => $regime,
            ':img1' => $img1,
            ':img2' => $img2,
            ':img3' => $img3,
            ':img4' => $img4
        ]);
    }

    public static function getRegimes() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM regime ORDER BY libelle ASC");
        return $stmt->fetchAll();
    }

    public static function decrementStock($id, $qty) {
        global $pdo;
        $sql = "UPDATE menu SET quantite_restante = quantite_restante - :qty 
                WHERE menu_id = :id AND quantite_restante >= :qty";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':qty' => $qty, ':id' => $id]);
    }

    public static function incrementStock($id, $qty) {
        global $pdo;
        $sql = "UPDATE menu SET quantite_restante = quantite_restante + :qty 
                WHERE menu_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':qty' => $qty, ':id' => $id]);
    }
}
?>