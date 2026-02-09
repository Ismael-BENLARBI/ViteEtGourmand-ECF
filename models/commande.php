<?php
class Commande {

    // 1. Créer la commande principale
    public static function create($userId, $total, $frais, $reduction, $nom, $prenom, $adresse, $cp, $ville, $phone, $heure, $instructions) {
        global $pdo;

        // Génération d'un numéro de commande unique
        $numeroCommande = 'CMD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $sql = "INSERT INTO commande (
                    numero_commande,
                    utilisateur_id, 
                    prix_total, 
                    prix_livraison,      /* <--- CORRECTION ICI : C'est bien 'prix_livraison' */
                    montant_reduction, 
                    adresse_livraison, 
                    code_postal_livraison, 
                    ville_livraison, 
                    telephone, 
                    heure_livraison, 
                    instructions,
                    statut, 
                    date_commande
                ) VALUES (
                    :num, :user, :total, :frais, :reduc,
                    :adr, :cp, :ville,
                    :phone, :heure, :instr,
                    'en_attente', NOW()
                )";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':num'   => $numeroCommande,
            ':user'  => $userId,
            ':total' => $total,
            ':frais' => $frais,
            ':reduc' => $reduction,
            ':adr'   => $adresse,
            ':cp'    => $cp,
            ':ville' => $ville,
            ':phone' => $phone,
            ':heure' => $heure,
            ':instr' => $instructions
        ]);

        return $pdo->lastInsertId();
    }

    // 2. Ajouter une ligne de menu à la commande
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

    // Récupérer toutes les commandes d'un utilisateur
    public static function getAllByUser($userId) {
        global $pdo;
        
        $sql = "SELECT * FROM commande 
                WHERE utilisateur_id = :userId 
                ORDER BY date_commande DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        
        return $stmt->fetchAll();
    }

    // Récupérer le CONTENU (les menus) d'une commande
    // Récupérer le CONTENU (les menus) d'une commande
    public static function getDetails($commandeId) {
        global $pdo;
        // ATTENTION : Il faut bien sélectionner m.menu_id
        $sql = "SELECT d.*, m.titre, m.image_principale, m.menu_id 
                FROM commande_detail d 
                JOIN menu m ON d.menu_id = m.menu_id 
                WHERE d.commande_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $commandeId]);
        return $stmt->fetchAll();
    }

    // Récupérer TOUTES les commandes (Pour l'Admin)
    public static function getAll() {
        global $pdo;
        // On récupère la commande ET le nom du client (table utilisateur)
        $sql = "SELECT c.*, u.nom, u.prenom 
                FROM commande c
                JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
                ORDER BY c.date_commande DESC";
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Supprimer une commande et son contenu
    public static function delete($id) {
        global $pdo;
        
        // 1. On supprime d'abord les détails (les plats contenus dans la commande)
        $stmt = $pdo->prepare("DELETE FROM commande_detail WHERE commande_id = :id");
        $stmt->execute([':id' => $id]);

        // 2. Ensuite, on supprime la commande elle-même
        $stmt = $pdo->prepare("DELETE FROM commande WHERE commande_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // --- GESTION EMPLOYÉ ---

    // 1. Annuler une commande (Spécifique Employé avec Motif)
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

    // 2. Mettre à jour le statut (Utilisé par Admin et Employé)
    public static function updateStatus($id, $statut) {
        global $pdo;
        $sql = "UPDATE commande SET statut = :statut WHERE commande_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':statut' => $statut,
            ':id' => $id
        ]);
    }

    // 3. Récupérer une commande par ID (Indispensable pour l'envoi d'email)
    public static function getById($id) {
        global $pdo;
        // On fait une jointure pour avoir l'email et le nom du client
        $sql = "SELECT c.*, u.email, u.nom, u.prenom 
                FROM commande c 
                JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id 
                WHERE c.commande_id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}