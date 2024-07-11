

# API Framework

## Introduction

Sthom Back est un framework API léger basé sur SlimPHP, conçu pour faciliter la gestion du routage, de l'authentification et de la persistance des données. Ce framework inclut des fonctionnalités personnalisées telles que l'ORM, le système d'annotations pour les routes, et des middlewares pour la sécurité.

## Table des Matières

1. [Introduction](#introduction)
2. [Installation et Configuration](#installation-et-configuration)
3. [Démarrage Rapide](#démarrage-rapide)
4. [Architecture](#architecture)
5. [Utilisation des Modules](#utilisation-des-modules)
   - [Routage](#routage)
   - [Contrôleurs](#contrôleurs)
   - [Middleware](#middleware)
   - [Modèles et ORM](#modèles-et-orm)
   - [Services](#services)
   - [Sécurité](#sécurité)
6. [Tests et Débogage](#tests-et-débogage)
7. [Déploiement](#déploiement)
8. [FAQ et Ressources](#faq-et-ressources)
9. [Glossaire](#glossaire)

## Installation et Configuration

### Pré-requis

- PHP 7.4 ou supérieur
- Composer
- Serveur Web (Apache ou Nginx)
- Base de Données (MySQL, PostgreSQL, etc.)

### Installation

Clonez le dépôt et installez les dépendances :

```bash
git clone https://github.com/SebastienThomasDEV/sthom-back.git
cd sthom-back
composer install
```

### Configuration

Copiez le fichier `.env.example` en `.env` et modifiez-le selon vos besoins :

```env
APP_ENV='dev'
DB_HOST='localhost'
DB_PORT=3306
DB_NAME='api'
DB_USER='admin'
DB_PASS='admin'
JWT_USER_TABLE='user'
JWT_USER_IDENTIFIER='email'
JWT_PRIVATE_KEY_PATH='config/jwt/private.pem'
JWT_PUBLIC_KEY_PATH='config/jwt/public.pem'
JWT_ISSUER='http://localhost:8000'
```

## Démarrage Rapide

### Exemple de Création d'une Route

Créez un contrôleur dans le répertoire `src/Controller` :

```php
<?php

namespace Sthom\Back\Controller;

use Sthom\Back\AbstractController;use Sthom\Back\Annotations\Route;

class ExampleController extends AbstractController
{
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public function base(): array
    {
        return $this->send(['message' => 'Hello World']);
    }
}
```

Lancez le serveur intégré PHP :

```bash
php -S localhost:8000 -t public
```

Visitez `http://localhost:8000` pour voir votre nouvelle route en action.

## Architecture

Le framework est structuré de manière modulaire, avec des composants pour le routage, les contrôleurs, les middlewares, les modèles, et les services. Voici un aperçu de la structure des dossiers :

```
your-project/
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Kernel/
│   │   ├── Framework/
│   │   ├── Utils/
│   │   └── Kernel.php
│   ├── Model/
│   ├── Repository/
│   └── Service/
├── public/
│   ├── .htaccess
│   └── index.php
├── tests/
├── .env
├── composer.json
└── README.md
```

## Utilisation des Modules

### Routage

Le routage est géré via des annotations dans les contrôleurs.

### Contrôleurs

Les contrôleurs traitent les requêtes HTTP et renvoient des réponses.

### Middleware

Les middlewares ajoutent des fonctionnalités comme l'authentification.

### Modèles et ORM

L'ORM custom permet de gérer les interactions avec la base de données.

### Services

Les services encapsulent des fonctionnalités réutilisables.

### Sécurité

L'authentification est gérée via des tokens JWT.

## Tests et Débogage

### Installation de PHPUnit

```bash
composer require --dev phpunit/phpunit
```

### Exemple de Test

```php
<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
```

## Déploiement

### Préparation du Serveur

Installez Apache et PHP, puis configurez le virtual host.

### Transfert des Fichiers

Utilisez `scp` ou un outil de déploiement pour transférer les fichiers.

### Installer les Dépendances

```bash
composer install --no-dev --optimize-autoloader
```

## FAQ et Ressources

### FAQ

1. **Comment configurer une nouvelle route ?**
2. **Comment sécuriser une route avec JWT ?**

### Ressources Utiles

- [Documentation Slim](https://www.slimframework.com/docs/v4/)
- [Documentation PHP](https://www.php.net/docs.php)
- [Documentation Composer](https://getcomposer.org/doc/)

## Glossaire

- **Annotation** : Métadonnées ajoutées au code source.
- **API** : Interface de programmation d'application.
- **Controller** : Composant qui gère les requêtes HTTP.
- **Middleware** : Composant qui ajoute des fonctionnalités aux requêtes.
- **ORM** : Mappage objet-relationnel.

---

Pour plus d'informations, veuillez consulter la documentation complète dans le répertoire `docs/`.
