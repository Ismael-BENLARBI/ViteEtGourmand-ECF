<?php

class Auth {

    // Vérifie si l'utilisateur est connecté (Tout rôle confondu)
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Vérifie si c'est un ADMIN (Rôle ID = 1)
    public static function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // On vérifie role_id == 1
        if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Vérifie si c'est un STAFF (Admin=1 OU Employé=2)
    // Utilisé pour les menus, avis, horaires
    public static function checkStaff() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role_id'], [1, 2])) {
            header('Location: index.php?page=login');
            exit;
        }
    }
    
    // Vérifie si c'est un EMPLOYÉ (Rôle ID = 2) uniquement
    public static function checkEmploye() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 2) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}