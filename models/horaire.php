<?php
require_once __DIR__ . '/../config/db.php';

class Horaire {
    public static function getAll() {
        global $pdo;
        // On trie par 'ordre' pour avoir Lundi en premier
        $sql = "SELECT * FROM horaire ORDER BY ordre ASC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function update($id, $creneau) {
        global $pdo;
        $sql = "UPDATE horaire SET creneau = :creneau WHERE horaire_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':creneau' => $creneau, ':id' => $id]);
    }
}