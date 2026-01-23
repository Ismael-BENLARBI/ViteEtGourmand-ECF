CREATE DATABASE IF NOT EXISTS vite_et_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
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
    FOREIGN KEY (theme_id) REFERENCES theme(theme_id) ON DELETE SET NULL,
    regime_id INT,
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

INSERT INTO role (libelle) VALUES ('Administrateur'), ('Employe'), ('Utilisateur');
INSERT INTO regime (libelle) VALUES ('Classique'), ('Végétarien'), ('Vegan'), ('Halal'), ('Sans Gluten');
INSERT INTO theme (libelle) VALUES ('Noël'), ('Pâques'), ('Mariage'), ('Anniversaire'), ('Entreprise');
INSERT INTO categorie_plat (libelle) VALUES ('Entrée'), ('Plat'), ('Dessert');