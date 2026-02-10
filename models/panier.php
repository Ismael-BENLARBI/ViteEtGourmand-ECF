<?php
require_once __DIR__ . '/../config/db.php';

class Panier {

    // 1. Charger le panier depuis la BDD (au login)
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

    // 2. Sauvegarder le panier actuel (à chaque ajout/suppression)
    public static function saveForUser($userId, $panierSession) {
        global $pdo;

        // A. On vide d'abord l'ancienne sauvegarde de cet utilisateur
        $sqlDelete = "DELETE FROM panier_sauvegarde WHERE utilisateur_id = :uid";
        $stmtDel = $pdo->prepare($sqlDelete);
        $stmtDel->execute([':uid' => $userId]);

        // B. Si le panier est vide, on s'arrête là
        if (empty($panierSession)) return;

        // C. On insère les nouveaux éléments
        $sqlInsert = "INSERT INTO panier_sauvegarde (utilisateur_id, menu_id, quantite) VALUES (:uid, :mid, :qty)";
        $stmtInsert = $pdo->prepare($sqlInsert);

        foreach ($panierSession as $menuId => $qty) {
            $stmtInsert->execute([
                ':uid' => $userId,
                ':mid' => $menuId,
                ':qty' => $qty
            ]);
        }
    }

    // 3. Supprimer la sauvegarde (après paiement)
    public static function clearSavedCart($userId) {
        global $pdo;
        $sql = "DELETE FROM panier_sauvegarde WHERE utilisateur_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
    }
}