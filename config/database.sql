SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `commande_detail`;
DROP TABLE IF EXISTS `avis`;
DROP TABLE IF EXISTS `panier_sauvegarde`;
DROP TABLE IF EXISTS `image`;
DROP TABLE IF EXISTS `commande`;
DROP TABLE IF EXISTS `menu`;
DROP TABLE IF EXISTS `contact`;
DROP TABLE IF EXISTS `horaire`;
DROP TABLE IF EXISTS `utilisateur`;
DROP TABLE IF EXISTS `theme`;
DROP TABLE IF EXISTS `regime`;
DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `role` (`role_id`, `libelle`) VALUES
(1, 'Administrateur'),
(2, 'Employe'),
(3, 'Utilisateur');

CREATE TABLE `theme` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `theme` (`theme_id`, `libelle`) VALUES
(1, 'Noël'),
(2, 'Pâques'),
(3, 'Mariage'),
(4, 'Anniversaire'),
(5, 'Entreprise');

CREATE TABLE `regime` (
  `regime_id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`regime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `regime` (`regime_id`, `libelle`) VALUES
(1, 'Classique'),
(2, 'Végétarien'),
(3, 'Vegan'),
(4, 'Halal'),
(5, 'Sans Gluten');

CREATE TABLE `horaire` (
  `horaire_id` int(11) NOT NULL AUTO_INCREMENT,
  `jour` varchar(20) NOT NULL,
  `creneau` varchar(150) NOT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`horaire_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `horaire` (`horaire_id`, `jour`, `creneau`, `ordre`) VALUES
(1, 'Lundi', '11h30 - 14h30 / 18h30 - 20h30', 1),
(2, 'Mardi', '11h30 - 14h30 / 18h30 - 22h30', 2),
(3, 'Mercredi', '11h30 - 14h30 / 18h30 - 22h30', 3),
(4, 'Jeudi', '11h30 - 14h30 / 18h30 - 22h30', 4),
(5, 'Vendredi', '11h30 - 14h30 / 18h30 - 23h00', 5),
(6, 'Samedi', '18h30 - 23h30', 6),
(7, 'Dimanche', '11h30 - 15h30', 7);

-- --------------------------------------------------------
-- UTILISATEURS
-- --------------------------------------------------------

CREATE TABLE `utilisateur` (
  `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse_postale` varchar(255) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `code_postal` varchar(20) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`utilisateur_id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de l'ADMINISTRATEUR par défaut (Mdp: Admin123!)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse_postale`, `ville`, `code_postal`, `role_id`) VALUES
(1, 'Admin', 'Principal', 'admin@vite-et-gourmand.fr', '$2y$10$6P83kGf3OUmsQ0p8wcR9AujFQ.RElKXPReixXUCdQpiwRz7qvbJdu', '0600000000', 'Siège Social', 'Bordeaux', '33000', 1);

-- --------------------------------------------------------
-- MENU & COMPOSANTS
-- --------------------------------------------------------

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `prix_par_personne` decimal(10,2) NOT NULL,
  `nombre_personne_min` int(11) NOT NULL DEFAULT 1,
  `quantite_restante` int(11) NOT NULL DEFAULT 0,
  `image_principale` varchar(255) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `regime_id` int(11) DEFAULT NULL,
  `image_entree` varchar(255) DEFAULT NULL,
  `image_plat` varchar(255) DEFAULT NULL,
  `image_dessert` varchar(255) DEFAULT NULL,
  `description_entree` text DEFAULT NULL,
  `description_plat` text DEFAULT NULL,
  `description_dessert` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `conditions` text DEFAULT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `theme_id` (`theme_id`),
  KEY `regime_id` (`regime_id`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`theme_id`) ON DELETE SET NULL,
  CONSTRAINT `menu_ibfk_2` FOREIGN KEY (`regime_id`) REFERENCES `regime` (`regime_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `alt_text` varchar(100) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`image_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `image_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- COMMANDES & PANIER
-- --------------------------------------------------------

CREATE TABLE `commande` (
  `commande_id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_commande` varchar(50) NOT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `date_prestation` date DEFAULT NULL,
  `heure_livraison` time NOT NULL,
  `adresse_livraison` varchar(255) NOT NULL,
  `ville_livraison` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `code_postal_livraison` varchar(20) NOT NULL,
  `prix_menu` decimal(10,2) DEFAULT 0.00,
  `nombre_personne` int(11) DEFAULT NULL,
  `prix_livraison` decimal(10,2) NOT NULL,
  `prix_total` decimal(10,2) DEFAULT NULL,
  `montant_reduction` decimal(10,2) DEFAULT 0.00,
  `statut` varchar(50) DEFAULT 'en_attente',
  `motif_annulation` text DEFAULT NULL,
  `mode_contact_annulation` varchar(50) DEFAULT NULL,
  `pret_materiel` tinyint(1) DEFAULT 0,
  `restitution_materiel` tinyint(1) DEFAULT 0,
  `utilisateur_id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`commande_id`),
  UNIQUE KEY `numero_commande` (`numero_commande`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`),
  CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `commande_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande` (`commande_id`),
  KEY `fk_menu` (`menu_id`),
  CONSTRAINT `fk_commande` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`commande_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `panier_sauvegarde` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `panier_sauvegarde_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`) ON DELETE CASCADE,
  CONSTRAINT `panier_sauvegarde_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- AVIS & CONTACT
-- --------------------------------------------------------

CREATE TABLE `avis` (
  `avis_id` int(11) NOT NULL AUTO_INCREMENT,
  `note` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'en_attente',
  `date_avis` datetime DEFAULT current_timestamp(),
  `utilisateur_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`avis_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`),
  CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `contact` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_visiteur` varchar(100) DEFAULT NULL,
  `email_visiteur` varchar(100) NOT NULL,
  `sujet` varchar(100) DEFAULT NULL,
  `contenu_message` text NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `est_traite` tinyint(1) DEFAULT 0,
  `reponse` text DEFAULT NULL,
  `date_reponse` datetime DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;