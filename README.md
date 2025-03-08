# Les Maestros de la Symfony

## Description
Les Maestros de la Symfony est un site e-commerce de vente d'instruments de musique en ligne. Il comprend un parcours de vente complet ainsi qu'un back-office dédié aux administrateurs pour la gestion du catalogue et des commandes.

## Auteurs
Projet réalisé par **Dorian, Kevin et Anthony**.

## Technologies utilisées
- Symfony 6.4
- PHP 8.1
- HTML / SCSS
- TypeScript
- Webpack Encore

## Installation et mise en route

### Prérequis
Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :
- PHP 8.1 et Composer
- Node.js et npm
- Symfony CLI
- Une base de données compatible avec Symfony (MySQL)

### Étapes d'installation
1. Clonez le projet :
   ```sh
   git clone (https://github.com/NairoD34/Les-Maestros.git)
   cd les-Maestros
   ```
2. Installez les dépendances PHP avec Composer :
   ```sh
   composer install
   ```
3. Installez les dépendances front-end avec npm :
   ```sh
   npm install
   ```
4. Configurez votre fichier `.env` en renseignant les paramètres de connexion à la base de données.
5. Exécutez les migrations de la base de données :
   ```sh
   symfony console doctrine:migrations:migrate ou alors symfony console d:m:m
   ```

### Démarrage du projet
1. Lancez le serveur Symfony en arrière-plan :
   ```sh
   symfony server:start -d
   ```
2. Compilez le TypeScript en continu avec Webpack Encore :
   ```sh
   npm run watch
   ```

### Accès au site
- Front-office : `http://127.0.0.1:8000`
- Back-office : `http://127.0.0.1:8000/admin`



