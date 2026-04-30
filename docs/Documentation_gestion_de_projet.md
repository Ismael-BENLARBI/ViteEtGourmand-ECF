## DOCUMENTATION DE GESTION DE PROJET

## PROJET : VITE & GOURMAND

**Nature du projet :** Application Web de Gestion Traiteur (MVC)

**Responsable Projet :** Ismael BENLARBI

## 1. CONTEXTE ET OBJECTIFS

**1.1 La Demande**

Le projet "Vite & Gourmand" répond au besoin de digitalisation d'une activité de traiteur.
L'objectif était de concevoir une application web permettant :

- Aux clients de commander leurs repas en ligne.
- À l'administrateur de gérer son catalogue et ses ventes.
- Au personnel de suivre la production et la logistique.

**1.2 Le Périmètre (Scope)**

Le projet couvre l'ensemble du cycle de vie logiciel : de la conception (UML/Maquettes)
au déploiement en production, en passant par le développement "From Scratch" (PHP
Natif MVC).

## 2. MÉTHODOLOGIE ET OUTILS

Nous avons adopté une démarche structurée en 3 grandes phases : **Conception >
Développement > Déploiement**.

**2.1 Outils de Pilotage**

- **Versionning :** Git & GitHub (Gestion de configuration logicielle).
- **Conception & Diagrammes :** Outils de modélisation UML (Draw.io).
- **Design UI/UX :** Outils de maquettage (Figma).
- **IDE :** Visual Studio Code.
- **Serveur Local :** XAMPP (Apache/MariaDB).
- **Client FTP :** FileZilla.

## 3. PHASE 1 : CONCEPTION ET PRÉPARATION


Avant tout développement, une phase d'analyse a été réalisée pour sécuriser
l'architecture de l'application.

**3.1 Initialisation de l'Environnement**

- Création de l'arborescence locale du projet.
- Initialisation du dépôt Git local (git init).
- Création du dépôt distant sur **GitHub** et liaison sécurisée (git remote add origin).
- Premier commit de structure ("Initial commit") pour valider le flux de travail.

**3.2 Modélisation (UML et Merise)**

Trois types de diagrammes ont été réalisés pour structurer le projet :

1. **Diagramme de Cas d'Utilisation (Use Case) :**
    o Identification des acteurs : _Client_ (Visiteur/Connecté), _Admin_ , _Employé_.
    o Définition des fonctionnalités par acteur (ex: "Passer commande", "Valider
       un avis", "Gérer le stock").
2. **Diagramme de Séquence :**
    o Modélisation du parcours critique "Commande" : _Panier -> Connexion ->_
       _Choix Livraison -> Paiement/Validation -> Envoi Email_.
3. **Modèle Conceptuel de Données (MCD) :**
    o Conception de la base de données relationnelle.
    o Définition des entités ( _Utilisateur, Menu, Commande, Avis_ ) et des
       cardinalités (ex: Un utilisateur peut passer plusieurs commandes 1,n).

**3.3 Maquettage (UI/UX)**

Des maquettes graphiques ont été réalisées pour valider l'ergonomie avant le codage :

- **Version Mobile :** Approche "Mobile First" pour garantir l'accessibilité sur
    smartphone.
- **Version Desktop :** Adaptation des interfaces pour grands écrans (Dashboard
    Admin complet, Grille de menus).
- **Charte Graphique :** Choix des couleurs (Doré/Noir pour le côté "Premium") et de
    la typographie.

## 4. PHASE 2 : DÉVELOPPEMENT (RÉALISATION)


**4.1 Architecture MVC**

Mise en place d'une structure de dossiers stricte pour séparer les responsabilités :

- **Models :** Gestion des données (Classes PHP User, Menu...).
- **Views :** Interfaces HTML/PHP.
- **Controllers :** Logique d'aiguillage via un routeur central (index.php).

**4.2 Fonctionnalités Clés Développées**

1. **Authentification & Sécurité :** Système de Login/Register sécurisé (Hashage
    Bcrypt), gestion des rôles et récupération de mot de passe par Token.
2. **Panier Intelligent :** Développement d'un système hybride stockant le panier en
    Session (visiteur) puis en Base de Données (connecté) avec fusion automatique.
3. **Back-Office Admin :** Création d'un CRUD complet pour gérer les menus (upload
    d'images) et visualisation des statistiques (KPIs).
4. **Gestion des Commandes :** Interface de changement de statut en temps réel
    (AJAX) pour le staff.

## 5. PHASE 3 : DÉPLOIEMENT ET PRODUCTION

**5.1 Mise en Ligne**

- **Hébergement :** Choix de la solution PaaS **AlwaysData**.
- **Migration BDD :** Export de la base locale (.sql) et import sur le serveur de
    production (phpMyAdmin).
- **Transfert FTP :** Configuration spécifique de **FileZilla** (Mode Passif, Connexion
    Simple) pour contourner les blocages TLS.

**5.2 Configuration SMTP**

L'envoi d'email via la fonction native PHP étant bloqué par les antispams, nous avons
intégré la librairie **PHPMailer**.

- Configuration d'un serveur SMTP authentifié.
- Mise en place des templates HTML pour les confirmations de commande et la
    réinitialisation de mot de passe.

## 6. GESTION DES RISQUES ET ALÉAS RENCONTRÉS


```
Risque / Aléa Impact Solution Apportée
```
```
Incompatibilité
OS
```
```
Erreur critique 500 sur
Linux (Sensibilité à la
casse).
```
```
Renommage strict de tous les
dossiers/fichiers (ex: models → Models)
pour respecter les standards PSR.
```
```
Emails non
reçus
```
```
Blocage des emails par
Gmail/Outlook.
```
```
Abandon de mail() natif au profit de
PHPMailer avec authentification SMTP.
```
```
Conflits Git
```
```
Risque d'écrasement de
code.
```
```
Utilisation de branches et vérification
systématique (git status) avant les push.
```
```
Connexion FTP Timeout (Délai d'attente
dépassé).
```
```
Passage en connexion FTP non-sécurisée
(Port 21) pour le transfert initial.
```
## 7. CONCLUSION

Le projet "Vite & Gourmand" a permis de mettre en œuvre une méthodologie complète
de gestion de projet web.

La phase de **conception** (UML/Maquettes) a été déterminante pour structurer la base
de données et éviter des refontes majeures en cours de développement.

La phase de **développement** a permis de consolider les compétences en PHP Objet et
architecture MVC.

Enfin, la phase de **déploiement** a confronté le projet aux réalités de l'hébergement web
(droits d'accès, configuration serveur, délivrabilité emails).

L'application est aujourd'hui fonctionnelle, stable, et prête à être utilisée par le client
final.


