# 🍽️ Vite & Gourmand - Application de Commande en Ligne

![Status](https://img.shields.io/badge/Status-Terminé-success)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-00758F)

## 📄 Contexte du projet
Ce projet a été réalisé dans le cadre de l'évaluation **ECF (Évaluation en Cours de Formation)** pour le titre de Développeur Web.
Une application web complète de gestion de traiteur permettant aux clients de commander des menus authentiques et à l'administration de gérer les stocks, les commandes et la messagerie en temps réel.

| Technologie | Usage |
| :--- | :--- |
| **PHP 8+** | Logique serveur et architecture MVC |
| **MySQL** | Base de données relationnelle |
| **JavaScript (AJAX/Fetch)** | Mises à jour fluides sans rechargement de page |
| **Bootstrap 5** | Framework CSS pour un design responsive |
| **PHPMailer** | Gestion sécurisée des envois d'emails (SMTP) |

## ✨ Fonctionnalités Principales

### 🛍️ Coté Client
* **Catalogue dynamique :** Filtres de recherche instantanés via requêtes AJAX.
* **Galerie visuelle :** Vue détaillée des plats avec fonctionnalité d'agrandissement d'image.
* **Accès sécurisé :** Système complet d'inscription et de login pour les commandes.
* **Communication :** Module de contact direct avec l'administration.

### 🔴 Partie Administration (Back-Office)
* **Tableau de bord (Dashboard) :** Vue d'ensemble de l'activité.
* **Gestion des Commandes :** Suivi des statuts (En préparation, Livré...) et historique.
* **Gestion des Menus (CRUD) :** Création, Modification et Suppression des menus.
* **Statistiques :** Visualisation graphique du chiffre d'affaires (Données JSON).
* **Gestion des Utilisateurs :** Création de comptes employés et modération.
* **Gestion des avis :** Modération des avis client.
* **Gestion des horaires :** Modification des horaires d'ouverture et fermeture.
* **Messagerie :** Lecture et traitement des demandes de contact.

## 🚀 Installation & Configuration

1.  **Cloner le dépôt :**
    ```bash
    git clone https://github.com/Ismael-BENLARBI/ViteEtGourmand-ECF.git
    ```

2.  **Base de données :**
    * Ouvrir PHPMyAdmin.
    * Créer une base de données nommée `vite_et_gourmand`.
    * Importer le fichier `database.sql` fourni à la racine.

3.  **Configuration :**
    * Vérifier les paramètres de connexion dans `config/db.php` (si nécessaire).

## 🐳 Docker (Validation compétence)

Le projet inclut une stack Docker complète:

- `web` : PHP 8.2 + Apache + extensions `pdo_mysql` et `mongodb`
- `db` : MySQL 8 avec import automatique de `config/database.sql`
- `phpmyadmin` : interface MySQL
- `mongo` : MongoDB pour journal d'activite NoSQL

### Démarrage

```bash
docker compose up -d --build
```

### Accès

- Application: `http://localhost:8000`
- phpMyAdmin: `http://localhost:8080`
- MongoDB: `mongodb://localhost:27017`

## 🧾 NoSQL (Validation compétence)

Le site reste basé sur MySQL pour les données métier. MongoDB est utilisé pour un besoin complémentaire:

- journal d'activite des connexions réussies (email, role, ip, date)
- stockage NoSQL non bloquant (si Mongo est indisponible, le site fonctionne quand meme)
- visualisation dans l'onglet admin `Activite NoSQL`

Variables utiles (voir `.env.example`) : `MONGO_DSN`, `MONGO_DB`, `MONGO_ACTIVITY_COLLECTION`.

## 🔐 Identifiants de Démonstration

Pour tester l'application intégralement, voici les comptes pré-configurés :

| Rôle | Email | Mot de passe | Permissions |
| :--- | :--- | :--- | :--- |
| **Administrateur** | `admin@vite-et-gourmand.fr` | `admin123@` | Accès Total + Stats + Users |
| **Employé** | `employe1@vite-et-gourmand.fr` | `employe123@` | Gestion Commandes + Avis |
| **Client** | `client@vite-et-gourmand.fr` | `client123@` | Commander + Historique |

---
*Projet développé par Ismaël BENLARBI - 2026*
