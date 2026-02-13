<?php
require_once __DIR__ . '/../config/db.php';

class Panier {
    public static function loadFromUser($userId) {
        global $pdo;
        $sql = "SELECT menu_id, quantite FROM panier_sauvegarde WHERE utilisateur_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        
        $panier = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $panier[$row['menu_id']] = $row['quantite'];
        }
        return $panier;
    }

    public static function saveForUser($userId, $panierSession) {
        global $pdo;
        $sqlDelete = "DELETE FROM panier_sauvegarde WHERE utilisateur_id = :uid";
        $stmtDel = $pdo->prepare($sqlDelete);
        $stmtDel->execute([':uid' => $userId]);

        if (empty($panierSession)) return;

        $sqlInsert = "INSERT INTO panier_sauvegarde (utilisateur_id, menu_id, quantite) VALUES (:uid, :mid, :qty)";
        $stmtInsert = $pdo->prepare($sqlInsert);

        $sqlCheck = "SELECT COUNT(*) FROM menu WHERE menu_id = :mid";
        $stmtCheck = $pdo->prepare($sqlCheck);

        foreach ($panierSession as $menuId => $qty) {
            
            $stmtCheck->execute([':mid' => $menuId]);
            $exists = $stmtCheck->fetchColumn();

            if ($exists > 0) {
                $stmtInsert->execute([
                    ':uid' => $userId,
                    ':mid' => $menuId,
                    ':qty' => $qty
                ]);
            }
        }
    }

    public static function clearSavedCart($userId) {
        global $pdo;
        $sql = "DELETE FROM panier_sauvegarde WHERE utilisateur_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
    }
}