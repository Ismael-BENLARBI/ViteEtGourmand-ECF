<?php
class Auth {

    // Fonction "Vigile" : Elle bloque l'entrée si on n'est pas Admin
    public static function checkAdmin() {
        
        // 1. Est-ce que la personne est connectée ?
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login&error=Vous devez être connecté pour accéder à cette page.');
            exit;
        }

        // 2. Est-ce que la personne a le badge "ADMIN" ?
        // (Rappel : on a défini 'role' => 'admin' dans le login_action)
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=home'); // On renvoie poliment à l'accueil
            exit;
        }

        // Si on arrive ici, c'est que tout est bon, le code continue !
    }
}