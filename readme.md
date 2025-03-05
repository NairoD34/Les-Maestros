# Les Maestros

Bienvenue sur le projet **Les Maestros**! Ce projet est un site web qui permet aux utilisateurs de gérer leurs produits, commandes et adresses dans un environnement convivial.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- [PHP](https://www.php.net/downloads) (version 7.4 ou supérieure)
- [Composer](https://getcomposer.org/download/)
- [Symfony CLI](https://symfony.com/download)
- [MAMP](https://www.mamp.info/en/) (pour le développement local)
- [NVM windows](https://github.com/coreybutler/nvm-windows)
- [NVM linux](https://github.com/nvm-sh/nvm) (pour gérer les versions de node et npm)

## Installation

1. **Clonez le dépôt :**

   ```bash
   git clone https://github.com/NairoD34/Les-Maestros
   cd Les-Maestros

2. **Installé les dépendances :**

    ```bash
    composer install
    npm install npm-watch

3. **Configurez votre environnement :**
Copiez le fichier .env en .env.local et modifiez les variables d'environnement selon vos besoins, notamment la connexion à la base de données.

4. **Créez la base de données :**
    ```bash
    symfony console make:migration
    symfony console d:m:m

## Lancer le serveur
    symfony serve -d
    npm run watch
## Features

## Coté user
1. **Inscription / Connexion :**
    Visitez la page d'inscription pour créer un compte.
    Connectez-vous avec vos identifiants.

2. **Gestion des produits :**
    Explorez les produits disponibles sur le site.
    Ajoutez des produits à votre panier pour les acheter.

3. **Gestion du panier :**
    Consultez, modifier ou valider votre panier afin d'accéder au tunel de vente.

4. **Gestion des commandes :**
    Consultez l'historique de vos commandes passées.

5. **Gestion des adresses :**
    Ajoutez et modifiez vos adresses de livraison.

6. **Déconnexion :**
    Déconnectez-vous depuis le tableau de bord.

## Coté admin

1. **Connexion :**
    Connectez-vous avec vos identifiants administratifs pour accéder au tableau de bord.

2. **Gestion des utilisateurs :**
    Consultez la liste des utilisateurs enregistrés.
    voir ou supprimez des comptes d'utilisateurs selon les besoins.

3. **Gestion des produits :**
    Ajoutez, modifiez ou supprimez des produits depuis le tableau de bord.
    Gérez les catégories de produits et les promotions associées.
4. **Gestion des commandes :**
    Consultez toutes les commandes passées par les utilisateurs.
    Suivez le statut des commandes et gérez les retours ou les problèmes.
5. **Gestion des promotions :**
    Consultez et créer modifier ou supprimer des promotions applicables à vos produits.
6. **Gestion des messages :**
    Consultez tous les messages laissés par les utilisateurs.
    Permet de voir l'adresse mail de l'utilisateur afin de lui répondre et de supprimer    les messages lorsqu'ils ont été traités
7. **Déconnexion :**
    Déconnectez-vous depuis le tableau de bord pour sécuriser votre session.
## Authors

- [@NairoD34](https://www.github.com/NairoD34)
- [@Somenae](https://www.github.com/Somenae)
- [@Tontico](https://www.github.com/Tontico)


