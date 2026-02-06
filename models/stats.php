<?php
require_once __DIR__ . '/../config/db.php';

class Stats {

    // 1. Chiffre d'Affaires sur une période donnée
    public static function getChiffreAffaires($dateDebut = null, $dateFin = null) {
        global $pdo;
        $sql = "SELECT SUM(prix_total) as total FROM commande WHERE statut != 'annulee'";
        $params = [];

        if ($dateDebut && $dateFin) {
            $sql .= " AND date_commande BETWEEN :debut AND :fin";
            $params = [':debut' => $dateDebut . ' 00:00:00', ':fin' => $dateFin . ' 23:59:59'];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // 2. Le Menu le plus vendu sur une période
    public static function getBestSeller($dateDebut = null, $dateFin = null) {
        global $pdo;
        $sql = "SELECT m.titre, SUM(d.quantite) as total_ventes 
                FROM commande_detail d
                JOIN menu m ON d.menu_id = m.menu_id
                JOIN commande c ON d.commande_id = c.commande_id
                WHERE c.statut != 'annulee'";
        
        $params = [];
        if ($dateDebut && $dateFin) {
            $sql .= " AND c.date_commande BETWEEN :debut AND :fin";
            $params = [':debut' => $dateDebut . ' 00:00:00', ':fin' => $dateFin . ' 23:59:59'];
        }

        $sql .= " GROUP BY d.menu_id ORDER BY total_ventes DESC LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    // 3. Commandes par mois (pour le graphique/tableau) - 12 derniers mois
    public static function getOrdersByMonth() {
        global $pdo;
        // On formate la date en "Année-Mois" (ex: 2023-10) et on compte
        $sql = "SELECT DATE_FORMAT(date_commande, '%Y-%m') as mois, COUNT(*) as total 
                FROM commande 
                WHERE statut != 'annulee'
                GROUP BY mois 
                ORDER BY mois DESC 
                LIMIT 12";
        return $pdo->query($sql)->fetchAll();
    }
}