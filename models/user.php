<?php
require_once __DIR__ . '/../config/db.php';

class User {

    // --- 1. INSCRIPTION (CREATE) ---
    public static function create($nom, $prenom, $email, $password, $adresse, $code_postal, $ville) {
        global $pdo;

        // Sécurité : Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $role_id = 3; // 3 = Client

        // ⚠️ CORRECTION ICI : Nom de la table 'utilisateur' et colonnes exactes
        $sql = "INSERT INTO utilisateur (nom, prenom, email, password, role_id, adresse_postale, code_postal, ville) 
                VALUES (:nom, :prenom, :email, :pass, :role, :adresse, :cp, :ville)";
        
        $stmt = $pdo->prepare($sql);
        
        try {
            return $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':pass' => $hashed_password,
                ':role' => $role_id,
                ':adresse' => $adresse,
                ':cp' => $code_postal,
                ':ville' => $ville
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // --- 2. CONNEXION (FIND BY EMAIL) ---
    public static function findByEmail($email) {
        global $pdo;

        // ⚠️ CORRECTION ICI : On cherche dans 'utilisateur'
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch();
    }
}