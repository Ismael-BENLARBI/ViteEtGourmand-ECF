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

    // --- 7. ADMIN : Récupérer TOUS les utilisateurs ---
    public static function getAll() {
        global $pdo;
        $sql = "SELECT * FROM utilisateur ORDER BY role_id ASC, nom ASC";
        return $pdo->query($sql)->fetchAll();
    }

    // --- 8. ADMIN : Changer le rôle (AJAX) ---
    public static function updateRole($id, $roleId) {
        global $pdo;
        $sql = "UPDATE utilisateur SET role_id = :role WHERE utilisateur_id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':role' => $roleId, ':id' => $id]);
    }

    // --- 9. ADMIN : Supprimer un utilisateur (AJAX) ---
    // --- 9. ADMIN : Supprimer un utilisateur ET toutes ses données (Cascade) ---
    public static function delete($id) {
        global $pdo;
        
        try {
            // Début de la transaction (sécurité)
            $pdo->beginTransaction();

            // ÉTAPE 1 : Supprimer les AVIS de cet utilisateur
            $stmt = $pdo->prepare("DELETE FROM avis WHERE utilisateur_id = :id");
            $stmt->execute([':id' => $id]);

            // ÉTAPE 2 : Supprimer les COMMANDES
            // D'abord, on doit récupérer les ID des commandes pour supprimer leur contenu (détails)
            $stmt = $pdo->prepare("SELECT commande_id FROM commande WHERE utilisateur_id = :id");
            $stmt->execute([':id' => $id]);
            $commandeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($commandeIds)) {
                // On transforme le tableau d'IDs en une chaîne (ex: "1, 5, 8") pour la requête SQL
                $idsString = implode(',', array_map('intval', $commandeIds));

                // a) Supprimer les détails (plats commandés)
                // On suppose que ta table s'appelle 'commande_detail' (vérifie le nom exact dans ta BDD si ça plante)
                $pdo->exec("DELETE FROM commande_detail WHERE commande_id IN ($idsString)");

                // b) Supprimer les commandes
                $stmt = $pdo->prepare("DELETE FROM commande WHERE utilisateur_id = :id");
                $stmt->execute([':id' => $id]);
            }

            // ÉTAPE 3 : Supprimer l'UTILISATEUR
            $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE utilisateur_id = :id");
            $stmt->execute([':id' => $id]);

            // Si tout s'est bien passé, on valide les changements
            $pdo->commit();
            return true;

        } catch (Exception $e) {
            // En cas d'erreur, on annule tout (Rollback) pour ne pas casser la base
            $pdo->rollBack();
            return false;
        }
    }

    // Modifier le mot de passe
    // Modifier le mot de passe
    public static function updatePassword($id, $newPassword) {
        global $pdo;
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // CORRECTION ICI : 'password' au lieu de 'mot_de_passe'
        $sql = "UPDATE utilisateur SET password = :mdp WHERE utilisateur_id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':mdp' => $hashedPassword,
            ':id' => $id
        ]);
    }
    
    // Vérifier l'ancien mot de passe
    public static function verifyPassword($id, $passwordInput) {
        global $pdo;
        
        // CORRECTION ICI : 'password' au lieu de 'mot_de_passe'
        $sql = "SELECT password FROM utilisateur WHERE utilisateur_id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        
        // CORRECTION ICI AUSSI : on vérifie $user['password']
        if ($user && password_verify($passwordInput, $user['password'])) {
            return true;
        }
        return false;
    }

}