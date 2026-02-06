<?php

class Auth {

    // 1. Vérifie si l'utilisateur est admin (Sinon redirige)
    public static function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si pas connecté OU pas admin -> Dehors
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=home');
            exit;
        }
    }

    // 2. Vérifie simplement si l'utilisateur est connecté (Sinon redirige vers Login)
    // C'EST CETTE FONCTION QUI TE MANQUAIT
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si l'utilisateur n'est pas connecté
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login'); // On le renvoie se connecter
            exit;
        }
    }
}