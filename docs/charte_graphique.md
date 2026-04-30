## CHARTE GRAPHIQUE & UI KIT

## PROJET : VITE ET GOURMAND

**Version :** 1.

**Date :** Février 2026

**Auteur :** Ismael BENLARBI

## 1. IDENTITÉ VISUELLE

**1.1 Mission & Valeurs**

_Cette section définit l'âme du projet._

- **Nom du projet :** Vite et Gourmand
- **Slogan :** L'excellence traiteur livrée chez vous!
- **Logo :**

**1.2 Ambiance Générale**

Le design repose sur l'élégance et la gourmandise. L'interface utilise des formes douces
et arrondies ("Pilule"), des ombres portées subtiles pour la profondeur, et un contraste
fort entre le **Bordeaux (Passion/Vin)** et le **Doré (Premium/Qualité)** , le tout sur un fond
**Beige (Chaleur/Naturel)**.

## 2. PALETTE DE COULEURS

**2.1 Couleurs Principales**

```
Couleur Nom
```
```
Code
HEX Usage Principal^
```
```
<span style="color:#8B2635">■</span>
```
```
Rouge
Bordeaux #8B^
```
```
Titres, Boutons
d'action, Navigation
active, Prix.
```
```
<span style="color:#D8A85E">■</span> Or / Doré #D8A85E
```
```
Sous-titres, Survol
(Hover), Badges,
Étoiles, Focus.
```

```
Couleur Nom
```
```
Code
HEX Usage Principal^
```
```
<span style="color:#F2F0ED">■</span> Beige
Coquille
```
```
#F2F0ED Arrière-plan général
du site (body).
```
```
<span style="color:#FFFFFF; border:1px
solid #ccc">□</span> Blanc Pur^ #FFFFFF^
```
```
Fond des cartes,
Conteneurs,
Formulaires.
```
**2.2 Typographie & Neutres**

```
Couleur Nom Code
HEX
```
```
Usage
```
```
<span style="color:#333333">■</span>
```
```
Gris
Foncé #^
```
```
Texte courant,
Paragraphes.
```
```
<span style="color:#444444">■</span> Gris
Neutre
```
```
#444444 Textes secondaires.
```
```
<span style="color:#666666">■</span>
```
```
Gris
Muted #^
```
```
Labels, Mentions
légales, Détails.
```
```
<span style="color:#EEEEEE; border:1px
solid #ccc">□</span>
```
```
Gris
Clair
```
```
#EEEEEE Bordures légères,
Séparateurs.
```
**2.3 Couleurs de Statut (Feedback Système)**

- **Succès (Validé / Livré) :** Fond #D4EDDA / Texte #
- **Attention (En attente) :** Fond #FFF3CD / Texte #
- **Erreur (Annulé / Supprimer) :** Fond #F8D7DA / Texte #721c24 / Boutons
    #E74C3C
- **Information :** Fond #D1ECF1 / Texte #0C

## 3. TYPOGRAPHIE

**Police Principale :** Montserrat, sans-serif.

**3.1 Hiérarchie des Titres**

- **H1 (Titre de page) :** Montserrat, Weight 800 (ExtraBold), Uppercase. Couleur :
    #8B2635. Taille : ~2.5rem.


- **H2 (Titre de section) :** Montserrat, Weight 800, Uppercase. Couleur : #8B2635.
- **Sous-titres & Accents :** Montserrat (Italic), Weight 600. Couleur : #D8A85E.

**3.2 Corps de texte**

- **Paragraphes :** Montserrat ou Segoe UI. Weight 400. Couleur : #333 ou #444.
- **Liens :** Pas de soulignement par défaut. Couleur #6c757d. Au survol : Couleur
    #8B2635 + Soulignement.

## 4. COMPOSANTS UI (INTERFACE UTILISATEUR)

**4.1 Boutons (Buttons)**

Style "Pilule" très arrondi.

- **Border-Radius :** 50px
- **Padding :** 10px 25px (Standard) ou 15px 40px (CTA Large).
- **Typographie :** Uppercase, Bold (700/800).
- **État Normal :** Fond Bordeaux (#8B2635), Texte Blanc.
- **État Survol (Hover) :** Fond Doré (#D8A85E), Texte Blanc, Transform: translateY(-
    2px), Ombre portée.

**4.2 Cartes (Cards)**

Utilisées pour les menus, le panier, les formulaires de connexion et le dashboard.

- **Fond :** Blanc (#FFFFFF).
- **Bordure :** Aucune (border: none).
- **Arrondi (Border-Radius) :** 20px à 30px.
- **Ombre (Box-Shadow) :** Douce, parfois teintée. Ex: 0 10px 30px rgba(0,0,0,0.05)
    ou rgba(139, 38, 53, 0.05).
- **Animation :** Légère lévitation au survol sur les cartes cliquables (translateY(-
    5px)).

**4.3 Formulaires (Inputs)**

- **Fond :** #FAFAFA (Gris très pâle) ou #FDFDFD.
- **Bordure :** 1px solid #DDD.
- **Arrondi :** 10px à 15px.


- **Focus (Actif) :** Fond Blanc, Bordure Dorée (#D8A85E), Halo Doré (box-shadow
    rgba).

**4.4 Navigation (Onglets / Pills)**

- **Conteneur :** Fond transparent.
- **Élément inactif :** Fond Blanc, Bordure légère, Texte Bordeaux.
- **Élément Actif :** Fond Bordeaux (#8B2635), Texte Blanc, Ombre portée.
- **Interaction :** Changement de couleur et élévation au survol.

## 5. ÉLÉMENTS GRAPHIQUES

**5.1 Icones**

- **Source :** FontAwesome (Classes fa-*).
- **Couleur par défaut :** Doré (#D8A85E) pour les éléments décoratifs (étoiles,
    horloges).

**5.2 Images**

- **Style :** Coins arrondis (10px à 20px).
- **Upload :** Zone de dépôt avec bordure en pointillés (2px dashed #DDD) qui
    devient dorée au survol.
- **Ratio :** Images "cover" qui remplissent l'espace sans déformation.

## 6. GUIDELINES TECHNIQUES (CSS VARIABLES)

Pour faciliter le développement, voici les variables CSS correspondant à la charte :

CSS

:root {

/* --- PALETTE --- */

--color-primary: #8B2635; /* Bordeaux */

--color-secondary: #D8A85E; /* Doré */

--color-bg: #F2F0ED; /* Beige Fond */

--color-white: #FFFFFF;

--color-text: #333333;


--color-text-light: #666666;

## /* --- FEEDBACK --- */

--color-success: #198754;

--color-danger: #dc3545;

--color-warning: #ffc107;

## /* --- SHADOWS --- */

--shadow-soft: 0 10px 30px rgba(0,0,0,0.05);

--shadow-red: 0 10px 40px rgba(139, 38, 53, 0.05);

--shadow-gold: 0 5px 15px rgba(216, 168, 94, 0.3);

## /* --- BORDERS --- */

--radius-s: 10px; /* Inputs */

--radius-m: 20px; /* Cards */

--radius-l: 30px; /* Containers */

--radius-pill: 50px; /* Buttons */

## /* --- FONTS --- */

--font-main: 'Montserrat', sans-serif;

}

## 7. MAQUETTES

**7.1 Maquettes Desktop**




**7. 2 Maquettes mobile**



