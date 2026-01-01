CREATE TABLE role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE theme (
    theme_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE regime (
    regime_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE allergene (
    allergene_id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE horaire (
    horaire_id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(50) NOT NULL,
    heure_ouverture TIME,
    heure_fermeture TIME
);

CREATE TABLE contact (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    email_visiteur VARCHAR(100) NOT NULL,
    sujet VARCHAR(100),
    contenu_message TEXT,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    est_traite BOOLEAN DEFAULT FALSE
);

CREATE TABLE Utilisateur (
    utilisateur_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    prenom VARCHAR(50),
    telephone VARCHAR(50),
    ville VARCHAR(50),
    pays VARCHAR(50),
    adresse_postale VARCHAR(255),
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

CREATE TABLE plat (
    plat_id INT AUTO_INCREMENT PRIMARY KEY,
    titre_plat VARCHAR(100),
    photo VARCHAR(255)
);

CREATE TABLE menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100),
    nombre_personne_min INT,
    prix_par_personne DOUBLE,
    description TEXT,
    quantite_restante INT,
    image_principale VARCHAR(255),
    regime_id INT,
    theme_id INT,
    FOREIGN KEY (regime_id) REFERENCES regime(regime_id),
    FOREIGN KEY (theme_id) REFERENCES theme(theme_id)
);

CREATE TABLE image (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255),
    alt_text VARCHAR(100),
    menu_id INT NOT NULL,
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE
);

CREATE TABLE commande (
    commande_id INT AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(50),
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_prestation DATE,
    heure_livraison VARCHAR(50),
    prix_menu DOUBLE,
    nombre_personne INT,
    prix_livraison DOUBLE,
    statut VARCHAR(50),
    pret_materiel BOOLEAN DEFAULT FALSE,
    restitution_materiel BOOLEAN DEFAULT FALSE,
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(utilisateur_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

CREATE TABLE avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    note VARCHAR(50),
    description TEXT,
    statut VARCHAR(50),
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(utilisateur_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

CREATE TABLE menu_plat (
    menu_id INT NOT NULL,
    plat_id INT NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE,
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE
);

CREATE TABLE plat_allergene (
    plat_id INT NOT NULL,
    allergene_id INT NOT NULL,
    PRIMARY KEY (plat_id, allergene_id),
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE,
    FOREIGN KEY (allergene_id) REFERENCES allergene(allergene_id) ON DELETE CASCADE
);