<?php
class Avis {
    
    public static function create($menuId, $userId, $note, $description) {
        global $pdo;
        $sql = "INSERT INTO avis (menu_id, utilisateur_id, note, description, date_avis, statut) 
                VALUES (:mid, :uid, :note, :desc, NOW(), 'en_attente')";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':mid' => $menuId,
            ':uid' => $userId,
            ':note' => $note,
            ':desc' => $description
        ]);
    }

    public static function getLastThree() {
        global $pdo;
        $sql = "SELECT a.*, u.prenom, u.nom, m.titre 
                FROM avis a
                JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
                JOIN menu m ON a.menu_id = m.menu_id
                WHERE a.statut = 'valide'
                ORDER BY a.date_avis DESC 
                LIMIT 3";
        return $pdo->query($sql)->fetchAll();
    }

    public static function getAllAdmin() {
        global $pdo;
        $sql = "SELECT a.*, u.prenom, u.nom, m.titre 
                FROM avis a
                JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
                JOIN menu m ON a.menu_id = m.menu_id
                ORDER BY 
                    CASE WHEN a.statut = 'en_attente' THEN 1 ELSE 2 END,
                    a.date_avis DESC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function validate($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE avis SET statut = 'valide' WHERE avis_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM avis WHERE avis_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}