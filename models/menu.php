<?php
class Menu {

    // Récupérer tous les menus avec filtres multiples
    public static function getAll($filters = []) {
        global $pdo;

        // 1. Début de la requête de base
        $sql = "SELECT m.*, t.libelle as theme_nom, r.libelle as regime_nom 
                FROM menu m
                LEFT JOIN theme t ON m.theme_id = t.theme_id
                LEFT JOIN regime r ON m.regime_id = r.regime_id
                WHERE 1=1"; 
        // "WHERE 1=1" est une astuce : ça permet d'ajouter des "AND ..." sans se soucier si c'est le premier ou pas.

        $params = [];

        // 2. Filtre Thème
        if (!empty($filters['theme']) && $filters['theme'] !== 'all') {
            $sql .= " AND m.theme_id = :theme";
            $params[':theme'] = $filters['theme'];
        }

        // 3. Filtre Régime (On cherche le nom du régime dans la table regime)
        if (!empty($filters['regime']) && $filters['regime'] !== 'all') {
            $sql .= " AND r.libelle = :regime";
            $params[':regime'] = $filters['regime'];
        }

        // 4. Filtre Prix Max
        if (!empty($filters['prix'])) {
            $sql .= " AND m.prix_par_personne <= :prix";
            $params[':prix'] = $filters['prix'];
        }

        // 5. Filtre Personnes Min (Le menu doit accepter au moins ce nombre)
        // Note: Ici on vérifie si le menu est faisable pour ce nombre. 
        // Si le client demande pour 2 pers mais que le menu est "Dès 8 pers", on ne l'affiche pas.
        if (!empty($filters['pers'])) {
            $sql .= " AND m.nombre_personne_min <= :pers";
            $params[':pers'] = $filters['pers'];
        }

        // On termine par le tri
        $sql .= " ORDER BY m.titre ASC";

        // 6. Exécution
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // 2. Récupérer la liste des Thèmes (pour le menu déroulant)
    public static function getThemes() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM theme ORDER BY libelle ASC");
        return $stmt->fetchAll();
    }

    // 3. Récupérer un menu par ID (inchangé)
// --- RÉCUPÉRER UN MENU PAR SON ID (POUR LA PAGE DÉTAIL) ---
    public static function getById($id) {
        global $pdo;

        // On fait des JOINTURES (LEFT JOIN) pour récupérer le NOM du thème et du régime
        // au lieu de juste leur ID (1, 2...).
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

    // Récupérer la liste des plats d'un menu spécifique
    public static function getPlatsByMenuId($menu_id) {
        global $pdo;
        
        // On joint la table plat via la table de liaison menu_plat
        $sql = "SELECT p.* FROM plat p
                JOIN menu_plat mp ON p.plat_id = mp.plat_id
                WHERE mp.menu_id = :menu_id
                ORDER BY FIELD(p.categorie, 'entree', 'plat', 'dessert')"; 
                // L'ordre FIELD permet de forcer l'affichage Entrée -> Plat -> Dessert
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // --- CRÉER UN NOUVEAU MENU ---
    // Mise à jour de la signature de la fonction et de la requête SQL
    // --- CRÉER UN NOUVEAU MENU (Mise à jour avec Régime) ---
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

    // --- SUPPRIMER UN MENU ---
    public static function delete($id) {
        global $pdo;
        // Optionnel : On pourrait d'abord supprimer l'image du dossier, mais restons simple pour l'instant.
        $stmt = $pdo->prepare("DELETE FROM menu WHERE menu_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // --- MODIFIER UN MENU ---
    // --- MISE À JOUR COMPLÈTE (FORMULAIRE) ---
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

    // --- RÉCUPÉRER TOUS LES RÉGIMES ---
    public static function getRegimes() {
        global $pdo;
        // On récupère ID et Libellé pour remplir le select
        $stmt = $pdo->query("SELECT * FROM regime ORDER BY libelle ASC");
        return $stmt->fetchAll();
    }

    // --- GESTION DES STOCKS (MOUVEMENTS) ---

    // 1. Déduire du stock (Lors de la commande)
    public static function decrementStock($id, $qty) {
        global $pdo;
        // La condition "AND quantite_restante >= :qty" empêche de passer en négatif
        $sql = "UPDATE menu SET quantite_restante = quantite_restante - :qty 
                WHERE menu_id = :id AND quantite_restante >= :qty";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':qty' => $qty, ':id' => $id]);
    }

    // 2. Remettre en stock (Lors d'une annulation)
    public static function incrementStock($id, $qty) {
        global $pdo;
        $sql = "UPDATE menu SET quantite_restante = quantite_restante + :qty 
                WHERE menu_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':qty' => $qty, ':id' => $id]);
    }
}
?>