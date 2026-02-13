<?php
require_once __DIR__ . '/../config/db.php';

class User {

    public static function create($nom, $prenom, $email, $password, $adresse, $code_postal, $ville) {
        global $pdo;

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $role_id = 3;

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

    public static function findByEmail($email) {
        global $pdo;
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public static function getById($id) {
        global $pdo;
        $sql = "SELECT * FROM utilisateur WHERE utilisateur_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public static function update($id, $nom, $prenom, $email, $telephone, $adresse, $cp, $ville) {
        global $pdo;
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

    public static function getAll() {
        global $pdo;
        $sql = "SELECT * FROM utilisateur ORDER BY role_id ASC, nom ASC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function updateRole($id, $roleId) {
        global $pdo;
        $sql = "UPDATE utilisateur SET role_id = :role WHERE utilisateur_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':role' => $roleId, ':id' => $id]);
    }

    public static function delete($id) {
        global $pdo;
        
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("DELETE FROM avis WHERE utilisateur_id = :id");
            $stmt->execute([':id' => $id]);

            $stmt = $pdo->prepare("SELECT commande_id FROM commande WHERE utilisateur_id = :id");
            $stmt->execute([':id' => $id]);
            $commandeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($commandeIds)) {
                $idsString = implode(',', array_map('intval', $commandeIds));

                $pdo->exec("DELETE FROM commande_detail WHERE commande_id IN ($idsString)");

                $stmt = $pdo->prepare("DELETE FROM commande WHERE utilisateur_id = :id");
                $stmt->execute([':id' => $id]);
            }

            $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE utilisateur_id = :id");
            $stmt->execute([':id' => $id]);

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public static function updatePassword($id, $newPassword) {
        global $pdo;
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE utilisateur SET password = :mdp WHERE utilisateur_id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':mdp' => $hashedPassword,
            ':id' => $id
        ]);
    }
    
    public static function verifyPassword($id, $passwordInput) {
        global $pdo;
        
        $sql = "SELECT password FROM utilisateur WHERE utilisateur_id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($passwordInput, $user['password'])) {
            return true;
        }
        return false;
    }

    public static function getByEmail($email) {
        global $pdo;
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public static function setResetToken($email, $token) {
        global $pdo;
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "UPDATE utilisateur SET reset_token = :token, reset_expires = :expires WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':token' => $token,
            ':expires' => $expires,
            ':email' => $email
        ]);
    }

    public static function getUserByResetToken($token) {
        global $pdo;
        $sql = "SELECT * FROM utilisateur WHERE reset_token = :token AND reset_expires > NOW()";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetch();
    }

    public static function clearResetToken($id) {
        global $pdo;
        $sql = "UPDATE utilisateur SET reset_token = NULL, reset_expires = NULL WHERE utilisateur_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public static function createByAdmin($nom, $prenom, $email, $password, $roleId) {
        global $pdo;
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilisateur (nom, prenom, email, password, role_id, adresse_postale, code_postal, ville) 
                VALUES (:nom, :prenom, :email, :pass, :role, '', '', '')";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':pass' => $hashedPassword,
            ':role' => $roleId
        ]);
    }

}