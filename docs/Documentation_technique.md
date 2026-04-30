## DOCUMENTATION TECHNIQUE

**Projet :** Vite & Gourmand

**Auteur :** Ismael BENLARBI

## 1. INTRODUCTION ET RÉFLEXIONS INITIALES

**1.1 Contexte du Projet**

Le projet "Vite & Gourmand" est une application web de gestion pour un traiteur.
L'objectif technique était de concevoir une solution robuste, sécurisée et évolutive,
capable de gérer des commandes en temps réel, sans dépendre de CMS lourds (comme
WordPress) ou de frameworks complexes pour cette version initiale.

**1.2 Approche Architecturale**

Le choix s'est porté sur une architecture **MVC (Modèle-Vue-Contrôleur)** stricte.

- **Pourquoi?** Pour séparer clairement la logique métier (PHP), l'accès aux données
    (SQL) et l'interface utilisateur (HTML/CSS).
- **Avantage :** Cette structure facilite la maintenance. Si l'on doit changer le design
    du site, on ne touche pas à la logique de base de données.

## 2. CHOIX TECHNOLOGIQUES (STACK TECHNIQUE)

```
Technologie Choix
Effectué
```
```
Justification Technique
```
```
Langage
Backend
```
## PHP 8.

```
(Natif)
```
```
Choisi pour sa performance et sa portabilité
universelle. L'absence de framework
(Symfony/Laravel) est un choix volontaire pour
maîtriser le fonctionnement bas niveau du protocole
HTTP et du routage.
```
```
Base de
Données
```
```
MySQL /
MariaDB
```
```
SGBD relationnel standard, robuste pour gérer les
relations complexes (Clients <-> Commandes <->
Menus).
```
```
Interface
SGBD
```
## PDO (PHP

```
Data Object)
```
```
Couche d'abstraction permettant de sécuriser les
requêtes (Requêtes préparées) et de changer de type
de base de données si nécessaire sans réécrire tout le
code.
```

```
Technologie
```
```
Choix
Effectué Justification Technique^
```
```
Frontend Bootstrap 5
```
```
Framework CSS permettant un développement rapide
("Rapid Prototyping") et garantissant la compatibilité
Mobile/Tablette (Responsive Design) sans CSS
complexe.
```
```
Interactions JavaScript (Fetch API)
```
```
Utilisation de JS natif (Vanilla) pour les appels
asynchrones (AJAX). Permet de mettre à jour le statut
des commandes côté Admin sans recharger la page.
```
```
Emailing PHPMailer
```
```
La fonction native mail() étant peu fiable (souvent
bloquée par les antispams), nous avons intégré la
librairie PHPMailer pour envoyer des emails
transactionnels via un serveur SMTP authentifié.
```
```
Hébergement AlwaysData
(PaaS)
```
```
Architecture Linux/Nginx performante avec accès SSH
et FTP. Support natif de PHP 8.x.
```
## 3. CONFIGURATION DE L'ENVIRONNEMENT

**3.1 Environnement de Développement (Local)**

- **Serveur :** XAMPP (Apache + MariaDB).
- **Structure des dossiers :**

Plaintext

/vite-et-gourmand

├── /Models # Logique BDD

├── /Views # HTML/PHP

├──/libs # PHPMailer

├── /config # db.php/database.sql

└── /assets # CSS/JS/Images

**3.2 Environnement de Production (Distant)**

Le déploiement sur serveur Linux (AlwaysData) a nécessité une normalisation stricte :


- **Sensibilité à la Casse (Case Sensitivity) :** Contrairement à Windows, Linux
    distingue models de Models. Tous les namespaces et appels require_once ont
    été harmonisés en **PascalCase** (ex: Models/User.php).
- **Connexion FTP :** Configuration spécifique de FileZilla en **Mode Passif** et
    **Connexion Simple** (Port 21) pour contourner les restrictions TLS et pare-feu.

## 4. CONCEPTION TECHNIQUE (VISUALISATION)

**4.1 Modèle Conceptuel de Données (MCD)**


**4.2 Diagramme de Cas d'Utilisation**

**4.3 Diagramme de Séquence**


## 5. SÉCURITÉ MISE EN PLACE

La sécurité a été intégrée dès la conception ("Security by Design") :

**5.1 Protection des Données**

- **Injections SQL :** Aucune variable utilisateur n'est concaténée directement dans
    les requêtes SQL. Utilisation exclusive de PDO::prepare() et execute().
- **Mots de Passe :** Stockage sous forme de hash via l'algorithme **Bcrypt**
    (password_hash).
- **Faille XSS :** Toutes les données affichées dans les Vues sont échappées via la
    fonction htmlspecialchars().


**5.2 Contrôle d'Accès (ACL)**

Un middleware manuel a été créé dans Utils/Auth.php.

- Chaque route sensible (ex: /admin_dashboard) commence par vérifier le rôle :

PHP

Auth::checkAdmin(); // Redirige si role_id != 1

Auth::checkStaff(); // Redirige si role_id != 1 ou 2

**5.3 Sécurité des Sessions**

- Régénération de l'ID de session à la connexion pour éviter le **Session Fixation**.
- Le panier est persistant : Fusion intelligente entre le panier "Visiteur"
    (Cookie/Session) et le panier "Client" (BDD) lors du login.

## 6. DOCUMENTATION DE DÉPLOIEMENT

**6.1 Prérequis Serveur**

- PHP 8.0+.
- Extensions requises : pdo_mysql, openssl (pour SMTP).

**6.2 Procédure d'Installation**

1. **Fichiers :** Transférer l'intégralité du dossier /www à la racine du serveur public
    (public_html ou www).
2. **Base de Données :**
    o Créer une base de données MySQL.
    o Importer le script database.sql.
3. **Configuration BDD :**
    o Éditer /config/db.php.
    o Mettre à jour $host, $dbname, $user, $password.
4. **Configuration SMTP :**
    o Éditer /Models/Mailer.php.
    o Renseigner les identifiants SMTP de l'hébergeur (Host, Username,
       Password, Port 587).

**6.3 Maintenance**


- **Logs :** En cas d'erreur 500, vérifier les logs Apache/Nginx.
- **Cache :** L'application n'utilise pas de cache serveur complexe, un simple
    rafraîchissement navigateur suffit pour voir les changements CSS/JS.


