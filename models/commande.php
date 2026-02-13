<?php
class Commande {
    public static function create($userId, $total, $frais, $reduction, $nom, $prenom, $adresse, $cp, $ville, $phone, $heure, $instructions, $datePrestation) {
        global $pdo;

        $numeroCommande = 'CMD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $sql = "INSERT INTO commande (
                    numero_commande,
                    utilisateur_id, 
                    prix_total, 
                    prix_livraison, 
                    montant_reduction,
                    nom,
                    prenom,
                    adresse_livraison, 
                    code_postal_livraison, 
                    ville_livraison, 
                    telephone, 
                    heure_livraison, 
                    instructions,
                    date_prestation,
                    statut, 
                    date_commande
                ) VALUES (
                    :num, :user, :total, :frais, :reduc,
                    :nom, :prenom,
                    :adr, :cp, :ville,
                    :phone, :heure, :instr,
                    :datePresta,
                    'en_attente', NOW()
                )";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':num'   => $numeroCommande,
            ':user'  => $userId,
            ':total' => $total,
            ':frais' => $frais,
            ':reduc' => $reduction,
            ':nom'   => $nom,
            ':prenom'=> $prenom,
            ':adr'   => $adresse,
            ':cp'    => $cp,
            ':ville' => $ville,
            ':phone' => $phone,
            ':heure' => $heure,
            ':instr' => $instructions,
            ':datePresta' => $datePrestation
        ]);

        return $pdo->lastInsertId();
    }

    public static function addDetail($commandeId, $menuId, $quantite, $prixUnitaire) {
        global $pdo;
        
        $sql = "INSERT INTO commande_detail (commande_id, menu_id, quantite, prix_unitaire) 
                VALUES (:cid, :mid, :qty, :prix)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cid' => $commandeId,
            ':mid' => $menuId,
            ':qty' => $quantite,
            ':prix' => $prixUnitaire
        ]);
    }

    public static function getAllByUser($userId) {
        global $pdo;
        
        $sql = "SELECT * FROM commande 
                WHERE utilisateur_id = :userId 
                ORDER BY date_commande DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        
        return $stmt->fetchAll();
    }

    public static function getDetails($commandeId) {
        global $pdo;
        $sql = "SELECT d.*, m.titre, m.image_principale, m.menu_id 
                FROM commande_detail d 
                JOIN menu m ON d.menu_id = m.menu_id 
                WHERE d.commande_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $commandeId]);
        return $stmt->fetchAll();
    }
    public static function getAll() {
        global $pdo;
        $sql = "SELECT c.*, u.nom, u.prenom 
                FROM commande c
                JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
                ORDER BY c.date_commande DESC";
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM commande_detail WHERE commande_id = :id");
        $stmt->execute([':id' => $id]);
        $stmt = $pdo->prepare("DELETE FROM commande WHERE commande_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function cancelByEmploye($id, $motif, $contact) {
        global $pdo;
        
        $sql = "UPDATE commande 
                SET statut = 'annulee', 
                    motif_annulation = :motif, 
                    mode_contact_annulation = :contact 
                WHERE commande_id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':motif' => $motif,
            ':contact' => $contact,
            ':id' => $id
        ]);
    }

    public static function updateStatus($id, $statut) {
        global $pdo;
        $sql = "UPDATE commande SET statut = :statut WHERE commande_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':statut' => $statut,
            ':id' => $id
        ]);
    }

    public static function getById($id) {
        global $pdo;
        $sql = "SELECT c.*, u.email, u.nom, u.prenom 
                FROM commande c 
                JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id 
                WHERE c.commande_id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}