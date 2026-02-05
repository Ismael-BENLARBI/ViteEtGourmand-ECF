<?php
require_once __DIR__ . '/../config/db.php';

class User {

    // --- 1. INSCRIPTION (CREATE) ---
    public static function create($nom, $prenom, $email, $password, $adresse, $code_postal, $ville) {
        global $pdo;

        // Sécurité : Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $role_id = 3; // 3 = Client

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
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // --- 3. RECUPERER PAR ID (GET BY ID) ---
    // Nouveau : Nécessaire pour pré-remplir le formulaire de profil
    public static function getById($id) {
        global $pdo;
        $sql = "SELECT * FROM utilisateur WHERE utilisateur_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // --- 4. MISE A JOUR PROFIL (UPDATE) ---
    // Nouveau : Nécessaire pour sauvegarder les modifications
    public static function update($id, $nom, $prenom, $email, $telephone, $adresse, $cp, $ville) {
        global $pdo;
        
        // Note : J'utilise 'adresse_postale' car c'est le nom de colonne vu dans ta fonction create()
        // Si ta colonne s'appelle 'adresse' tout court, change-le ici.
        $sql = "UPDATE utilisateur SET 
                nom = :nom, 
                prenom = :prenom, 
                email = :email, 
                telephone = :telephone, 
                adresse_postale = :adresse, 
                code_postal = :cp, 
                ville = :ville
                WHERE utilisateur_id = :id";
                
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':adresse' => $adresse,
            ':cp' => $cp,
            ':ville' => $ville
        ]);
    }
}