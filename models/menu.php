<?php
class Menu {

    // 1. Récupérer les menus (avec un filtre optionnel par thème)
    public static function getAll($theme_id = null) {
        global $pdo;
        
        // Requête de base
        $sql = "SELECT menu.*, 
                       theme.libelle AS theme_nom, 
                       regime.libelle AS regime_nom
                FROM menu
                LEFT JOIN theme ON menu.theme_id = theme.theme_id
                LEFT JOIN regime ON menu.regime_id = regime.regime_id";
        
        // Si un filtre est appliqué, on ajoute la condition WHERE
        if ($theme_id && $theme_id != 'all') {
            $sql .= " WHERE menu.theme_id = :theme_id";
        }
        
        $sql .= " ORDER BY menu.menu_id ASC";
        
        $stmt = $pdo->prepare($sql);
        
        // Si filtre, on lie le paramètre
        if ($theme_id && $theme_id != 'all') {
            $stmt->bindParam(':theme_id', $theme_id, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Récupérer la liste des Thèmes (pour le menu déroulant)
    public static function getThemes() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM theme ORDER BY libelle ASC");
        return $stmt->fetchAll();
    }

    // 3. Récupérer un menu par ID (inchangé)
    public static function getById($id) {
        global $pdo;
        $sql = "SELECT menu.*, theme.libelle AS theme_nom, regime.libelle AS regime_nom
                FROM menu
                LEFT JOIN theme ON menu.theme_id = theme.theme_id
                LEFT JOIN regime ON menu.regime_id = regime.regime_id
                WHERE menu.menu_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>