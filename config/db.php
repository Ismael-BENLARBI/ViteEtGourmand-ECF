<?php
// Informations de connexion Alwaysdata
$host     = 'mysql-vite-et-gourmand-projet.alwaysdata.net';
$dbname   = 'vite-et-gourmand-projet_bdd';       // Ex: jean-dupond_vite_et_gourmand
$username = 'vite-et-gourmand-projet';                    // Ton nom d'utilisateur Alwaysdata
$password = 'VEG-admin123!';                   // Le mot de passe défini dans l'admin Alwaysdata

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En production, on évite d'afficher $e->getMessage() pour des raisons de sécurité
    die("Erreur de connexion à la base de données.");
}