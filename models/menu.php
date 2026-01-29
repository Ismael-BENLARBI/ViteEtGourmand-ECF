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
    public static function create($titre, $description, $d_entree, $d_plat, $d_dessert, $prix, $min_personnes, $theme_id, $img_main, $img_entree, $img_plat, $img_dessert) {
        global $pdo;

        $sql = "INSERT INTO menu (
                    titre, description, description_entree, description_plat, description_dessert, 
                    prix_par_personne, nombre_personne_min, theme_id, 
                    image_principale, image_entree, image_plat, image_dessert, date_creation
                ) 
                VALUES (
                    :titre, :desc, :d_entree, :d_plat, :d_dessert,
                    :prix, :min, :theme, 
                    :img1, :img2, :img3, :img4, NOW()
                )";
        
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            ':titre' => $titre,
            ':desc'  => $description,
            ':d_entree' => $d_entree,
            ':d_plat'   => $d_plat,
            ':d_dessert'=> $d_dessert,
            ':prix'  => $prix,
            ':min'   => $min_personnes,
            ':theme' => $theme_id,
            ':img1'  => $img_main,
            ':img2'  => $img_entree,
            ':img3'  => $img_plat,
            ':img4'  => $img_dessert
        ]);
    }
}
?>