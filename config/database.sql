-- 1. CRÉATION DE LA BASE ET DES TABLES
DROP DATABASE IF EXISTS vite_et_gourmand;
CREATE DATABASE vite_et_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE vite_et_gourmand;

CREATE TABLE role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE theme (
    theme_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE regime (
    regime_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE allergene (
    allergene_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE horaire (
    horaire_id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(20) NOT NULL,
    heure_ouverture TIME NOT NULL,
    heure_fermeture TIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE utilisateur (
    utilisateur_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse_postale VARCHAR(255),
    ville VARCHAR(100),
    code_postal VARCHAR(20),
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
) ENGINE=InnoDB;

CREATE TABLE menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    prix_par_personne DECIMAL(10, 2) NOT NULL,
    nombre_personne_min INT NOT NULL DEFAULT 1,
    quantite_restante INT NOT NULL DEFAULT 0,
    image_principale VARCHAR(255),
    theme_id INT,
    regime_id INT,
    FOREIGN KEY (theme_id) REFERENCES theme(theme_id) ON DELETE SET NULL,
    FOREIGN KEY (regime_id) REFERENCES regime(regime_id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE plat (
    plat_id INT AUTO_INCREMENT PRIMARY KEY,
    titre_plat VARCHAR(100) NOT NULL,
    description TEXT,
    photo VARCHAR(255),
    categorie ENUM('entree', 'plat', 'dessert') NOT NULL
) ENGINE=InnoDB;

CREATE TABLE image (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(100),
    menu_id INT,
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE commande (
    commande_id INT AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(50) NOT NULL UNIQUE,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_prestation DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    adresse_livraison VARCHAR(255) NOT NULL, 
    ville_livraison VARCHAR(100) NOT NULL,
    code_postal_livraison VARCHAR(20) NOT NULL,
    prix_menu DECIMAL(10, 2) NOT NULL,
    nombre_personne INT NOT NULL,
    prix_livraison DECIMAL(10, 2) NOT NULL,
    statut VARCHAR(50) DEFAULT 'en_attente',
    pret_materiel BOOLEAN DEFAULT FALSE,
    restitution_materiel BOOLEAN DEFAULT FALSE,
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
) ENGINE=InnoDB;

CREATE TABLE avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    note INT NOT NULL,
    description TEXT,
    statut VARCHAR(50) DEFAULT 'en_attente',
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
) ENGINE=InnoDB;

CREATE TABLE contact (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    email_visiteur VARCHAR(100) NOT NULL,
    sujet VARCHAR(100),
    contenu_message TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    est_traite BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Tables de liaison (Ajoutées ici pour être sûr qu'elles existent)
CREATE TABLE menu_plat (
    menu_id INT,
    plat_id INT,
    PRIMARY KEY (menu_id, plat_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE,
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE plat_allergene (
    plat_id INT,
    allergene_id INT,
    PRIMARY KEY (plat_id, allergene_id),
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE,
    FOREIGN KEY (allergene_id) REFERENCES allergene(allergene_id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- 2. INSERTION DES DONNÉES

-- Rôles
INSERT INTO role (libelle) VALUES ('Administrateur'), ('Employe'), ('Utilisateur');

-- Régimes
INSERT INTO regime (libelle) VALUES ('Classique'), ('Végétarien'), ('Vegan'), ('Halal'), ('Sans Gluten');

-- Thèmes
INSERT INTO theme (libelle) VALUES ('Noël'), ('Pâques'), ('Mariage'), ('Anniversaire'), ('Entreprise');

-- Allergènes
INSERT INTO allergene (libelle) VALUES ('Gluten'), ('Lactose'), ('Oeufs'), ('Arachides'), ('Fruits à coque');

-- Utilisateur Admin (Mot de passe : admin123) - INDISPENSABLE POUR LE LOGIN
INSERT INTO utilisateur (nom, prenom, email, password, role_id) 
VALUES ('Chef', 'Julie', 'admin@viteetgourmand.fr', '$2y$10$Xk.v/Fg.v/Fg.v/Fg.v/Fg.v/Fg.v/Fg.v/Fg.v/Fg.v/Fg.v/Fg', 1);

-- Plats
INSERT INTO plat (titre_plat, description, photo, categorie) VALUES 
('Foie gras maison', 'Mi-cuit au torchon avec sa confiture de figues', 'foie_gras.jpg', 'entree'),
('Dinde aux marrons', 'Farcie et rôtie au four', 'dinde.jpg', 'plat'),
('Bûche Glacée', 'Parfum fruits rouges et vanille', 'buche.jpg', 'dessert'),
('Velouté de courge', 'Aux éclats de châtaigne', 'veloute.jpg', 'entree'),
('Risotto aux cèpes', 'Riz arborio crémeux', 'risotto.jpg', 'plat'),
('Salade de fruits', 'Fruits de saison frais', 'salade.jpg', 'dessert');

-- Menus
INSERT INTO menu (titre, description, prix_par_personne, quantite_restante, image_principale, theme_id, regime_id) VALUES 
('Menu de Noël', 'Le grand classique des fêtes pour réunir la famille autour de produits nobles.', 45.00, 50, 'menu_noel.jpg', 1, 1),
('Menu Végétarien', 'Une alternative gourmande et saine, cuisinée avec des produits du marché.', 32.00, 30, 'menu_vege.jpg', 4, 2), 
('Buffet Entreprise', 'Un assortiment complet sucré/salé idéal pour vos séminaires.', 25.00, 100, 'menu_entreprise.jpg', 5, 1);

-- Liaisons Menu <-> Plat
-- Menu Noël (1) : Foie gras(1), Dinde(2), Bûche(3)
INSERT INTO menu_plat (menu_id, plat_id) VALUES (1, 1), (1, 2), (1, 3);
-- Menu Végé (2) : Velouté(4), Risotto(5), Salade(6)
INSERT INTO menu_plat (menu_id, plat_id) VALUES (2, 4), (2, 5), (2, 6);

-- Liaisons Plat <-> Allergène
-- Bûche (3) contient Lactose(2) et Oeufs(3)
INSERT INTO plat_allergene (plat_id, allergene_id) VALUES (3, 2), (3, 3);
-- Risotto (5) contient Lactose(2)
INSERT INTO plat_allergene (plat_id, allergene_id) VALUES (5, 2);