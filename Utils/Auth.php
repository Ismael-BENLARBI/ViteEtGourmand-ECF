<?php

class Auth {

    public static function check() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public static function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public static function checkStaff() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role_id'], [1, 2])) {
            header('Location: index.php?page=login');
            exit;
        }
    }
    
    public static function checkEmploye() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 2) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}