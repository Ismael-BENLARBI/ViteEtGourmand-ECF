<?php
require_once __DIR__ . '/../config/db.php';

class Contact {

    // 1. Ajouter un message (Front)
    public static function add($nom, $email, $sujet, $contenu) {
        global $pdo;
        // J'utilise tes noms de colonnes exacts
        $sql = "INSERT INTO contact (nom_visiteur, email_visiteur, sujet, contenu_message) VALUES (:nom, :email, :sujet, :contenu)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':sujet' => $sujet,
            ':contenu' => $contenu
        ]);
    }

    // 2. Récupérer tous les messages (Admin)
    public static function getAll() {
        global $pdo;
        $sql = "SELECT * FROM contact ORDER BY date_envoi DESC";
        return $pdo->query($sql)->fetchAll();
    }

    // 3. Marquer comme Traité (AJAX Admin)
    public static function markAsHandled($id) {
        global $pdo;
        // On passe est_traite à 1
        $sql = "UPDATE contact SET est_traite = 1 WHERE message_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // 4. Supprimer un message (AJAX Admin)
    public static function delete($id) {
        global $pdo;
        // Vérifie bien que c'est 'message_id' ici
        $sql = "DELETE FROM contact WHERE message_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ... (Gardes les fonctions add, getAll, delete existantes) ...

    // 5. Enregistrer la réponse de l'admin
    public static function saveReply($id, $reponse) {
        global $pdo;
        $sql = "UPDATE contact SET reponse = :reponse, date_reponse = NOW(), est_traite = 1 WHERE message_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':reponse' => $reponse, ':id' => $id]);
    }
}