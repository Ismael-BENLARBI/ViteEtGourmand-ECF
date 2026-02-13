<?php
require_once __DIR__ . '/../config/db.php';

class Contact {
    public static function add($nom, $email, $sujet, $contenu) {
        global $pdo;
        $sql = "INSERT INTO contact (nom_visiteur, email_visiteur, sujet, contenu_message) VALUES (:nom, :email, :sujet, :contenu)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':sujet' => $sujet,
            ':contenu' => $contenu
        ]);
    }

    public static function getAll() {
        global $pdo;
        $sql = "SELECT * FROM contact ORDER BY date_envoi DESC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function markAsHandled($id) {
        global $pdo;
        $sql = "UPDATE contact SET est_traite = 1 WHERE message_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public static function delete($id) {
        global $pdo;
        $sql = "DELETE FROM contact WHERE message_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public static function saveReply($id, $reponse) {
        global $pdo;
        $sql = "UPDATE contact SET reponse = :reponse, date_reponse = NOW(), est_traite = 1 WHERE message_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':reponse' => $reponse, ':id' => $id]);
    }

    public static function getById($id) {
        global $pdo;
        $sql = "SELECT * FROM contact WHERE message_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}