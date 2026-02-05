<?php
class Avis {
    
    // Créer un avis (statut 'en_attente' par défaut via la BDD)
    public static function create($menuId, $userId, $note, $description) {
        global $pdo;
        $sql = "INSERT INTO avis (menu_id, utilisateur_id, note, description, date_publication, statut) 
                VALUES (:mid, :uid, :note, :desc, NOW(), 'en_attente')";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':mid' => $menuId,
            ':uid' => $userId,
            ':note' => $note,
            ':desc' => $description
        ]);
    }

    // --- MODIFICATION CRITIQUE ---
    // Récupérer les 3 derniers avis VALIDÉS pour l'accueil
    public static function getLastThree() {
        global $pdo;
        $sql = "SELECT a.*, u.prenom, u.nom, m.titre 
                FROM avis a
                JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
                JOIN menu m ON a.menu_id = m.menu_id
                WHERE a.statut = 'valide'  /* <-- SEULEMENT LES VALIDÉS */
                ORDER BY a.date_publication DESC 
                LIMIT 3";
        return $pdo->query($sql)->fetchAll();
    }

    // --- POUR L'ADMIN ---
    
    // 1. Récupérer TOUS les avis pour le dashboard
    public static function getAllAdmin() {
        global $pdo;
        $sql = "SELECT a.*, u.prenom, u.nom, m.titre 
                FROM avis a
                JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
                JOIN menu m ON a.menu_id = m.menu_id
                ORDER BY 
                    CASE WHEN a.statut = 'en_attente' THEN 1 ELSE 2 END, /* Les 'en attente' d'abord */
                    a.date_publication DESC";
        return $pdo->query($sql)->fetchAll();
    }

    // 2. Valider un avis
    public static function validate($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE avis SET statut = 'valide' WHERE avis_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // 3. Supprimer un avis
    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM avis WHERE avis_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}