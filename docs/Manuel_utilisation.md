## MANUEL D'UTILISATION

**Application :** Vite & Gourmand

**Auteur :** Ismael BENLARBI

## 1. ACCÈS ET CONNEXION

**1.1 Accès au site**

L'application est accessible via votre navigateur web à l'adresse suivante :

- **URL :** https://vite-et-gourmand-projet.alwaysdata.net

**1.2 Identifiants de Démonstration**

Pour tester les différentes fonctionnalités, trois niveaux d'accès sont pré-configurés :

```
Rôle Email (Login) Mot de passe Permissions
```
```
Administrateur
```
```
admin@vite-et-
gourmand.fr admin123@^
```
```
Accès total (Gestion
technique & commerciale)
```
```
Employé employe1@vite-et-
gourmand.fr
```
```
employe 123 @ Gestion quotidienne
(Commandes, Stocks, Avis)
```
```
Client
```
```
client@vite-et-
gourmand.fr client123@^
```
```
Commande, Panier, Espace
Perso
```
## 2. PARCOURS CLIENT (FRONT-OFFICE)

**2.1 Consultation et Recherche**

- **Catalogue :** Sur la page d'accueil ou l'onglet "Menus", visualisez l'ensemble des
    plats.
- **Filtres :** Utilisez la barre latérale pour trier les menus par :
    o **Thème :** (ex: Marocain, Italien, Japonais...).
    o **Régime :** (ex: Végétarien, Halal, Sans Gluten...).
    o **Prix :** (ex: Moins de 15€).
- **Détail :** Cliquez sur un menu pour voir sa composition détaillée (Entrée, Plat,
    Dessert) et les allergènes.

**2.2 Passer une Commande**


1. **Ajout au Panier :** Sélectionnez la quantité et cliquez sur "Ajouter".
    o _Note :_ Si vous n'êtes pas connecté, votre panier est sauvegardé
       temporairement. Dès votre connexion, il sera fusionné avec votre ancien
       panier sauvegardé.
2. **Validation :** Cliquez sur l'icône Panier > "Commander".
3. **Identification :** Connectez-vous ou créez un compte si ce n'est pas fait.
4. **Livraison :** Remplissez le formulaire de prestation :
    o Date et Heure souhaitée.
    o Adresse complète de livraison.
5. **Confirmation :** Après validation, vous recevrez instantanément un **email**
    **récapitulatif** (via SMTP) contenant le détail de votre commande et le montant
    total.

**2.3 Espace Client (Mon Compte)**

Accessible via le menu "Mon Compte" en haut à droite.

- **Mes Informations :** Modifiez votre nom, prénom, téléphone ou adresse.
- **Sécurité :** Changez votre mot de passe.
- **Historique des Commandes :**
    o Visualisez la liste de vos anciennes commandes.
    o **Action "Annuler" :** Tant que le statut est **"En attente"** , vous pouvez
       annuler votre commande vous-même. Le remboursement est simulé et le
       stock des produits est **automatiquement remis en vente**.
    o **Action "Modifier" :** (Si "En attente") Permet de remettre tout le contenu de
       la commande dans le panier pour l'ajuster (ajout/retrait de plats) avant de
       revalider.

## 3. ESPACE EMPLOYÉ (STAFF)

Accessible aux utilisateurs ayant le rôle "Employé".

**3.1 Tableau de Bord (Dashboard)**

Une vue d'ensemble affiche les commandes du jour et les tâches urgentes.

**3.2 Gestion des Commandes**

- **Suivi :** Liste complète des commandes avec filtres par date ou client.


- **Changement de Statut :** Modifiez l'état d'une commande (ex: "En préparation",
    "En livraison", "Livré").
       o _Note Technique :_ La mise à jour se fait en temps réel (AJAX) sans recharger
          la page.
- **Cas Spécial "Retour Matériel" :** Si vous passez une commande au statut
    **"Attente retour matériel"** , un email de relance est envoyé automatiquement au
    client pour lui rappeler de rendre les équipements (Chafing dishes, plats inox...).
- **Annulation :** Vous pouvez annuler une commande client. Un motif vous sera
    demandé (ex: "Rupture de stock imprévue") et sera enregistré.

**3.3 Gestion du Catalogue**

- **Créer un Menu :** Formulaire complet avec upload de 4 images (Menu, Entrée,
    Plat, Dessert).
- **Modifier/Supprimer :** Mise à jour des prix, descriptions ou retrait d'un menu
    obsolète.
- **Stocks :** Ajustement manuel des quantités disponibles.

**3.4 Autres Fonctionnalités**

- **Modération des Avis :** Validez ou supprimez les commentaires laissés par les
    clients avant qu'ils n'apparaissent sur le site public.
- **Horaires :** Mettez à jour les créneaux d'ouverture affichés en pied de page du
    site.

## 4. ESPACE ADMINISTRATEUR (SUPER-ADMIN)

Accessible uniquement au rôle "Admin". Il possède toutes les fonctionnalités de
l'Employé, avec des pouvoirs étendus.

**4.1 Statistiques (KPI)**

Visualisation stratégique de l'activité :

- **Chiffre d'Affaires :** Total des ventes validées sur la période.
- **Volume :** Nombre de commandes mensuelles.
- **Best Sellers :** Top 3 des menus les plus vendus.

**4.2 Gestion des Utilisateurs**

- **Liste des inscrits :** Vue globale de la base client.


- **Attribution des Rôles :** Possibilité de promouvoir un "Client" en "Employé"
    (Staff).
       o _Sécurité :_ Il est impossible de créer un autre Administrateur ou de
          supprimer un Administrateur depuis cette interface.
- **Suppression :** Supprimer un utilisateur efface également tout son historique
    (GDPR compliant).

**4.3 Messagerie Intégrée**

- **Boîte de Réception :** Centralise tous les messages envoyés via le formulaire
    "Contact" du site.
- **Traitement :**
    o **Lire :** Consulter le détail de la demande.
    o **Répondre :** Un champ de réponse permet d'écrire directement au client.
       Le message est envoyé par email (SMTP) et marqué comme "Traité" dans
       la base de données.
    o **Supprimer :** Effacer les messages traités.

## 5. ASSISTANCE EN CAS DE PROBLÈME

- **Mot de passe oublié :** Utilisez le lien "Mot de passe oublié" sur la page de
    connexion. Un lien de réinitialisation sécurisé (valable 1 heure) vous sera envoyé
    par email.
- **Bug technique :** Si une erreur survient (ex: Erreur 500), contactez le support
    technique. Les erreurs sont journalisées sur le serveur.


