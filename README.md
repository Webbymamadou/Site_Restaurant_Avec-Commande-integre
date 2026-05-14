# 🍽️ Le Gourmand - Site de Restaurant avec Commande Intégrée

Bienvenue dans le projet **Le Gourmand**, une application web moderne, dynamique et "white-label" conçue pour les restaurants gastronomiques, spécialisée dans les viandes braisées et grillées.

## 🚀 Présentation du Projet

Le Gourmand n'est pas seulement un site vitrine, c'est une plateforme complète permettant à un restaurateur de gérer sa présence en ligne et de recevoir des commandes directement depuis son site. L'application a été conçue avec une approche modulaire et personnalisable (White Label), permettant de changer l'identité visuelle du restaurant en quelques clics via un panneau de configuration.

## ✨ Fonctionnalités Principales

-   **Interface Gastronomique Premium** : Un design "Dark Mode" élégant, utilisant des animations fluides (AOS) et une typographie moderne pour mettre en valeur les plats.
-   **Menu Dynamique** : Affichage des plats par catégories, géré directement depuis la base de données.
-   **Système de Panier** : Possibilité pour les clients d'ajouter des produits à un panier persistant durant leur session.
-   **Gestion des Commandes** : Un tunnel de commande simple et efficace pour collecter les informations des clients et leurs choix.
-   **Panneau d'Administration (White Label)** : Interface dédiée pour modifier le nom du restaurant, le logo, les couleurs du thème, les coordonnées et les descriptions.
-   **Responsive Design** : Entièrement optimisé pour une utilisation sur smartphones, tablettes et ordinateurs.

## 🛠️ Technologies Utilisées

Le projet repose sur une stack technologique robuste et performante :

-   **Backend** : PHP 8.x avec l'extension **PDO** pour des interactions sécurisées avec la base de données.
-   **Frontend** : HTML5, CSS3 (Vanilla + Variables CSS) et JavaScript.
-   **Framework CSS** : Bootstrap 5 pour la structure et la réactivité.
-   **Base de Données** : MySQL.
-   **Animations** : AOS (Animate On Scroll) pour l'aspect premium.

## 📁 Structure du Projet

```text
Le Gourmand/
├── assets/             # Images, styles CSS et scripts JS
├── config.php          # Configuration centrale et connexion BDD
├── header.php          # En-tête réutilisable (Navigation)
├── footer.php          # Pied de page réutilisable
├── index.php           # Page d'accueil principale (Hero, Menu, Commande)
├── panier.php          # Gestion et affichage du panier
├── commandes.php       # Suivi et gestion des commandes (Admin)
├── parametres.php      # Configuration White-Label (Admin)
├── database.sql        # Schéma de la base de données
└── README.md           # Documentation du projet
```

## ⚙️ Installation et Configuration

1.  **Prérequis** : Avoir un serveur local type XAMPP, WAMP ou MAMP.
2.  **Base de données** : 
    - Créez une base de données nommée `gourmand`.
    - Importez le fichier `database.sql` fourni à la racine.
3.  **Configuration** :
    - Modifiez le fichier `config.php` si vos identifiants MySQL sont différents de ceux par défaut (`root` sans mot de passe).
4.  **Lancement** :
    - Placez le dossier dans votre répertoire `htdocs` ou `www`.
    - Accédez au site via `http://localhost/Le%20Gourmand/`.

## 🎨 Personnalisation

Grâce au système de paramètres intégré (`parametres.php`), vous pouvez modifier l'apparence du site sans toucher au code :
- Changer les couleurs principales (Primaire, Secondaire, Accent).
- Modifier le logo et l'image de fond (Hero).
- Mettre à jour les informations de contact instantanément.

---
*Développé avec passion pour offrir la meilleure expérience culinaire digitale.*