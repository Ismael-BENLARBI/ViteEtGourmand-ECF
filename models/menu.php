<?php
class Menu {

    // Récupérer tous les menus avec le nom du thème et du régime
    public static function getAll() {
        global $pdo;
        
        // On sélectionne TOUT le menu, PLUS le nom du thème et du régime
        $sql = "SELECT menu.*, 
                       theme.libelle AS theme_nom, 
                       regime.libelle AS regime_nom
                FROM menu
                LEFT JOIN theme ON menu.theme_id = theme.theme_id
                LEFT JOIN regime ON menu.regime_id = regime.regime_id
                ORDER BY menu.menu_id ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Récupérer un menu précis par son ID (pour plus tard)
    public static function getById($id) {
        global $pdo;
        
        $sql = "SELECT menu.*, 
                       theme.libelle AS theme_nom, 
                       regime.libelle AS regime_nom
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