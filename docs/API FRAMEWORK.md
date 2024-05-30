

# Documentation Technique du Framework basé sur Slim

## Sommaire

1. [Introduction](#1-introduction)
2. [Installation et Configuration](#2-installation-et-configuration)
3. [Démarrage Rapide](#3-démarrage-rapide)
4. [Architecture du Framework](#4-architecture-du-framework)
5. [Gestion des Dépendances](#5-gestion-des-dépendances)
6. [Module Routage](#6-module-routage)
7. [Module Contrôleurs](#7-module-contrôleurs)
8. [Module Middleware](#8-module-middleware)
9. [Module Modèles et ORM](#9-module-modèles-et-orm)
10. [Module Services](#10-module-services)
11. [Module Sécurité](#11-module-sécurité)
12. [Tests et Débogage](#12-tests-et-débogage)
13. [Déploiement](#13-déploiement)
14. [FAQ et Ressources](#14-faq-et-ressources)
15. [Glossaire](#15-glossaire)

## 1. Introduction
- Vue d'ensemble du framework
- Objectifs de la documentation

## 2. Installation et Configuration
- Pré-requis
- Installation via Composer
- Configuration initiale
- Structure des dossiers

## 3. Démarrage Rapide
- Exemple de création d'une application
- Définition des routes
- Gestion des requêtes et des réponses

## 4. Architecture du Framework
- Présentation des composants principaux
- Diagramme d'architecture
- Fonctionnement interne

## 5. Gestion des Dépendances
- Utilisation de Composer
- Autoloading PSR-4
- Gestion des versions et mises à jour

## 6. Module Routage
- Définition des routes
- Annotations de routes
- Lecture et enregistrement des routes
- Gestion des routes dynamiques
- Exécution de l'application

## 7. Module Contrôleurs
- Création et gestion des contrôleurs
- Injection de dépendances
- Envoi de réponses
- Gestion des erreurs
- Exemples complets de contrôleurs

## 8. Module Middleware
- Introduction aux middlewares
- Implémentation d'un middleware
- Ajout de middlewares à l'application Slim
- Exemples de middlewares
- Meilleures pratiques

## 9. Module Modèles et ORM
- Configuration de la base de données
- Utilisation de l'ORM custom
- Création et gestion des repositories
- Exemples de modèles et repositories
- Méthodes utiles de l'ORM custom
- Best practices

## 10. Module Services
- Définition et utilisation des services
- Exemple de création d'un service
- Injection de services dans les contrôleurs
- Exemples de services communs
- Création de services personnalisés

## 11. Module Sécurité
- Gestion des JWT
- Configuration des JWT
- Implémentation du service JWT
- Implémentation des middlewares de sécurité
- Bonnes pratiques de sécurité

## 12. Tests et Débogage
- Introduction aux tests
- Installation de PHPUnit
- Structure des tests
- Exemple de test unitaire
- Exemple de test fonctionnel
- Utilisation de Symfony VarDumper
- Journalisation
- Bonnes pratiques pour les tests et le débogage

## 13. Déploiement
- Introduction au déploiement
- Préparation de l'environnement
- Configuration du serveur web
- Déploiement de l'application
- Configuration du .htaccess
- Vérification du déploiement
- Automatisation du déploiement
- Bonnes pratiques de déploiement

## 14. FAQ et Ressources
- FAQ
  - Configuration des routes
  - Sécurisation des routes
  - Injection de services
  - Configuration de la base de données
  - Débogage de l'application
- Ressources utiles
  - Documentation officielle
  - Tutoriels et guides
  - Outils de développement
  - Outils de déploiement
  - Communautés et forums

## 15. Glossaire
- Définition des termes clés utilisés dans le framework

### 1. Introduction

### Vision et Objectifs du Framework

Le framework est conçu pour simplifier le développement d'applications web en fournissant une structure modulaire et extensible. Basé sur Slim, il combine la légèreté de Slim avec des outils puissants pour la gestion des routes, des contrôleurs, des middlewares, des modèles, et des services.

### Fonctionnalités Clés

- **Routage Simplifié** : Utilisation des annotations pour définir les routes de manière claire et concise.
- **Injection de Dépendances** : Facilite l'injection de services dans les contrôleurs pour un code plus modulaire et maintenable.
- **Gestion des Middlewares** : Ajout et gestion des middlewares, y compris les JWT pour l'authentification.
- **ORM Custom** : Un ORM simple pour gérer les interactions avec la base de données.
- **Services Utiles** : Intégration de services courants comme Logger, Mailer, Cache, etc.
- **Sécurité** : Utilisation des JWT pour sécuriser les routes.
- **Tests et Débogage** : Support pour les tests unitaires avec PHPUnit et outils de débogage intégrés.

---
### 2. Installation et Configuration

#### Prérequis
Avant d'installer le framework, assurez-vous que votre environnement de développement répond aux exigences suivantes :
- **PHP 7.4** ou supérieur
- **Composer** pour la gestion des dépendances
- Extensions PHP requises : `pdo`, `pdo_mysql`, `json`

#### Installation des dépendancs via Composer
Pour installer les framework, utilisez Composer en exécutant la commande suivante dans votre terminal :

```bash
composer install
```

Cette commande va créer un nouveau projet avec toutes les dépendances nécessaires.

#### Configuration Initiale (.env)
Après l'installation, configurez votre environnement en créant un fichier `.env` à la racine de votre projet. Voici un exemple de configuration :

```env
JWT_USER_TABLE='user'
JWT_USER_IDENTIFIER='email'
JWT_PRIVATE_KEY_PATH='config/jwt/private.pem'
JWT_PUBLIC_KEY_PATH='config/jwt/public.pem'
JWT_ISSUER='http://localhost:8000'

DB_HOST='localhost'
DB_PORT=3306
DB_NAME='api'
DB_USER='admin'
DB_PASS='admin'

APP_ENV='dev'
```

- **JWT_USER_TABLE** : Nom de la table des utilisateurs.
- **JWT_USER_IDENTIFIER** : Identifiant unique de l'utilisateur (par exemple, email).
- **JWT_PRIVATE_KEY_PATH** : Chemin vers la clé privée pour les JWT.
- **JWT_PUBLIC_KEY_PATH** : Chemin vers la clé publique pour les JWT.
- **JWT_ISSUER** : L'émetteur du JWT, généralement l'URL de votre application.
- **DB_HOST** : Hôte de la base de données.
- **DB_PORT** : Port de la base de données.
- **DB_NAME** : Nom de la base de données.
- **DB_USER** : Utilisateur de la base de données.
- **DB_PASS** : Mot de passe de la base de données.
- **APP_ENV** : Environnement de l'application (dev, prod, etc.).

#### Configuration de l'Application
Après avoir configuré le fichier `.env`, vous devez initialiser l'application pour qu'elle prenne en compte les configurations. Assurez-vous que le fichier `index.php` situé dans le dossier `public` charge bien les variables d'environnement et initialise le Kernel.

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Sthom\Back\Kernel\Kernel;

$kernel = Kernel::getInstance();
$kernel->run();
```

Cette configuration garantit que votre application est prête à fonctionner avec les paramètres définis dans le fichier `.env`.

---


### 3. Architecture du Framework

#### Aperçu de la Structure du Projet
La structure du projet est conçue pour être modulaire et intuitive, facilitant ainsi la navigation et le développement. Voici un aperçu de la structure typique d'un projet utilisant ce framework :

```
your-project/
├── config/
│   ├── jwt/
│   │   ├── private.pem
│   │   └── public.pem
├── public/
│   ├── .htaccess
│   └── index.php
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Kernel/
│   │   ├── Framework/
│   │   │   ├── Annotations/
│   │   │   ├── Middlewares/
│   │   │   ├── Model/
│   │   │   ├── Services/
│   │   │   └── Utils/
│   ├── Repository/
│   └── Kernel.php
├── tests/
├── .env
├── composer.json
└── vendor/
```

#### Description des Composants Principaux

1. **config/** : Contient les fichiers de configuration du projet. Le sous-dossier `jwt/` stocke les clés privée et publique utilisées pour la gestion des JWT.

2. **public/** : Contient les fichiers accessibles publiquement. Le fichier `.htaccess` est utilisé pour la réécriture des URL, et `index.php` est le point d'entrée principal de l'application.

3. **src/** : Contient tout le code source de l'application.
    - **Controller/** : Stocke les contrôleurs qui gèrent les requêtes HTTP et les réponses.
    - **Entity/** : Contient les entités de la base de données.
    - **Kernel/** : Contient le noyau du framework et les composants essentiels.
        - **Framework/** : Inclut les annotations, middlewares, modèles, services et utilitaires.
            - **Annotations/** : Définit les annotations utilisées dans le projet.
            - **Middlewares/** : Contient les middlewares pour gérer les requêtes et les réponses.
            - **Model/** : Implémente les fonctionnalités ORM et les modèles de la base de données.
            - **Services/** : Fournit des services réutilisables comme le Logger, le Mailer, etc.
            - **Utils/** : Contient des utilitaires comme les traits et les interfaces.
    - **Repository/** : Contient les repositories pour interagir avec la base de données.
    - **Kernel.php** : Fichier principal pour initialiser et exécuter le noyau du framework.

4. **tests/** : Contient les tests unitaires et fonctionnels de l'application.

5. **.env** : Fichier de configuration de l'environnement.

6. **composer.json** : Fichier de configuration de Composer pour la gestion des dépendances.

7. **vendor/** : Contient les dépendances installées par Composer.

#### Détails des Dossiers et Fichiers Clés

1. **config/jwt/private.pem** et **config/jwt/public.pem** : Clés pour signer et vérifier les JWT.

2. **public/.htaccess** : Fichier de configuration Apache pour la réécriture des URL, redirigeant toutes les requêtes vers `public/index.php`.

3. **public/index.php** : Point d'entrée principal de l'application. Charge les dépendances et initialise le Kernel.

4. **src/Kernel.php** : Fichier principal du noyau qui initialise les composants du framework.

5. **src/Kernel/Framework/Middlewares/** : Contient les middlewares, y compris `JwtMiddleware` pour gérer l'authentification JWT.

6. **src/Kernel/Framework/Services/** : Contient les services comme `LoggerService`, `MailerService`, `CacheService`, etc.

7. **src/Kernel/Framework/Annotations/Route.php** : Définition des annotations de route.

8. **src/Repository/** : Contient les classes de repository pour interagir avec les entités de la base de données.

9. **tests/** : Structure de tests pour vérifier le fonctionnement correct de l'application.

Cette structure modulaire permet une organisation claire et une maintenance facile de l'application, facilitant le développement et l'évolution du projet.

---

### 4. Module de Base

#### Structure du Projet
Le module de base constitue le cœur de votre application. Il comprend les configurations initiales et la structure fondamentale nécessaire pour le bon fonctionnement du framework.

```
your-project/
├── config/
│   ├── jwt/
│   │   ├── private.pem
│   │   └── public.pem
├── public/
│   ├── .htaccess
│   └── index.php
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Kernel/
│   │   ├── Framework/
│   │   │   ├── Annotations/
│   │   │   ├── Middlewares/
│   │   │   ├── Model/
│   │   │   ├── Services/
│   │   │   └── Utils/
│   ├── Repository/
│   └── Kernel.php
├── tests/
├── .env
├── composer.json
└── vendor/
```

#### Détails des Dossiers et Fichiers Clés

1. **config/**
   - **jwt/** : Contient les clés `private.pem` et `public.pem` utilisées pour la gestion des JWT.

2. **public/**
   - **.htaccess** : Fichier de configuration Apache pour la réécriture des URL.
   - **index.php** : Point d'entrée principal de l'application. Charge les dépendances et initialise le Kernel.

   ```php
   <?php
   require __DIR__ . '/../vendor/autoload.php';
   use Sthom\Back\Kernel\Kernel;

   $kernel = Kernel::getInstance();
   $kernel->run();
   ```

3. **src/**
   - **Controller/** : Dossier pour les contrôleurs qui gèrent les requêtes et les réponses HTTP.
   - **Entity/** : Contient les entités de la base de données.
   - **Kernel/** : Contient le noyau du framework et les composants essentiels.
     - **Framework/** : Inclut les annotations, middlewares, modèles, services et utilitaires.
       - **Annotations/** : Définit les annotations utilisées dans le projet.
       - **Middlewares/** : Contient les middlewares pour gérer les requêtes et les réponses.
       - **Model/** : Implémente les fonctionnalités ORM et les modèles de la base de données.
       - **Services/** : Fournit des services réutilisables comme le Logger, le Mailer, etc.
       - **Utils/** : Contient des utilitaires comme les traits et les interfaces.
   - **Repository/** : Contient les repositories pour interagir avec la base de données.
   - **Kernel.php** : Fichier principal pour initialiser et exécuter le noyau du framework.

   ```php
   <?php
   namespace Sthom\Back\Kernel;

   use Dotenv\Dotenv;
   use Exception;
   use Psr\Http\Message\ResponseInterface as ServerResponse;
   use Psr\Http\Message\ServerRequestInterface as ServerRequest;
   use Slim\Factory\AppFactory;
   use Sthom\Back\Kernel\Framework\Middlewares\JwtMiddleware;
   use Sthom\Back\Kernel\Framework\Model\Model;
   use Sthom\Back\Kernel\Framework\Services\Request;
   use Sthom\Back\Kernel\Framework\Utils\ClassReader;
   use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;
   use Throwable;

   class Kernel
   {
       use SingletonTrait;

       public final function run(): void
       {
           try {
               $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
               $env = $dotenv->load();
               $model = Model::getInstance();
               $model->config($env);
               $app = AppFactory::create();
               $isDevMode = $_ENV['APP_ENV'] === 'dev';
               $app->addErrorMiddleware($isDevMode , $isDevMode, $isDevMode);
               $app->add(new JwtMiddleware());
               $app->options('/{routes:.+}', function (ServerRequest $serverRequest, ServerResponse $response) {
                   return $response;
               });
               $routes = ClassReader::readRoutes();
               $app->add(new JwtMiddleware($routes));
               foreach ($routes as $route) {
                   $requestType = $route->getRequestType();
                   $path = $route->getPath();
                   $app->{$requestType}($path, function (ServerRequest $serverRequest, ServerResponse $serverResponse) use ($route) {
                       try {
                           Request::getInstance()->configure($serverRequest);
                           if ($serverRequest->getAttribute('error')) {
                               $serverResponse->getBody()->write(json_encode(['error' => true, 'message' => $serverRequest->getAttribute('error')]));
                           } else {
                               $controller = $route->getController();
                               $response = $controller->{$route->getFn()}(...$route->getParameters());
                               $serverResponse->getBody()->write(json_encode($response));
                           }
                           return $serverResponse
                               ->withHeader('Content-Type', 'application/json')
                               ->withHeader('Access-Control-Allow-Origin', '*')
                               ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                               ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                               ->withHeader('Access-Control-Max-Age', '86400');
                       } catch (Exception|Throwable $e) {
                           return $this->handleError($serverResponse, $e);
                       }
                   });
               }
               $app->run();
           } catch (Exception|Throwable $e) {
               $this->handleFatalError($e);
           }
       }

       private function handleError(ServerResponse $response, Throwable $exception): ServerResponse
       {
           $error = [
               'error' => true,
               'message' => $exception->getMessage(),
               'code' => $exception->getCode()
           ];
           $response->getBody()->write(json_encode($error));
           return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
       }

       private function handleFatalError(Throwable $exception): void
       {
           error_log($exception->getMessage());
           http_response_code(500);
           header('Content-Type: application/json');
           echo json_encode([
               'error' => true,
               'message' => $_ENV['APP_ENV'] === 'dev' ? $exception->getMessage() : 'An error occurred',
           ]);
           exit;
       }
   }
   ```

4. **tests/** : Contient les tests unitaires et fonctionnels de l'application.

5. **.env** : Fichier de configuration de l'environnement. Les variables d'environnement sont chargées au début de l'exécution du Kernel.

6. **composer.json** : Fichier de configuration de Composer pour la gestion des dépendances. Définissez les dépendances nécessaires pour votre projet.

```json
{
    "name": "sthom/back",
    "autoload": {
        "psr-4": {
            "Sthom\\Back\\": "src/"
        }
    },
    "authors": [
        {
            "name": "Sébastien THOMAS",
            "email": "74055963+SebastienThomasDEV@users.noreply.github.com"
        }
    ],
    "require-dev": {
        "symfony/var-dumper": "^6.4"
    },
    "require": {
        "slim/slim": "4.*",
        "slim/psr7": "^1.6",
        "firebase/php-jwt": "^6.10",
        "vlucas/phpdotenv": "^5.6",
        "ext-readline": "*",
        "ext-pdo": "*"
    }
}
```

7. **vendor/** : Contient les dépendances installées par Composer.

Cette section sur le module de base devrait vous fournir une compréhension claire de la structure fondamentale de votre projet et de ses composants principaux.

---

### 5. Développement de l'Application

#### Configuration de l'Application
Avant de commencer le développement, assurez-vous que votre application est correctement configurée. Vérifiez que le fichier `.env` est correctement renseigné avec les paramètres nécessaires pour votre environnement de développement.

#### Gestion des Routes
Les routes définissent les points d'entrée de votre application et mappent les URLs aux contrôleurs et actions correspondants. Utilisez des annotations pour définir les routes de manière claire et concise.

##### Définition des Routes
Les routes peuvent être définies directement dans les contrôleurs à l'aide de l'annotation `#[Route]`.

Exemple :

```php
<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;

class ExampleController extends AbstractController
{
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public function base(): array
    {
        return $this->send(['message' => 'Hello World']);
    }
}
```

##### Annotations de Routes
L'annotation `#[Route]` permet de spécifier le chemin (`path`), le type de requête (`requestType`) et si la route est protégée (`guarded`).

- **path** : Le chemin de la route.
- **requestType** : Le type de requête HTTP (GET, POST, PUT, DELETE, PATCH).
- **guarded** : Indique si la route est protégée par un middleware de sécurité (ex : JWT).

#### Création et Gestion des Contrôleurs
Les contrôleurs gèrent les requêtes et les réponses HTTP. Chaque contrôleur doit étendre `AbstractController` et définir des méthodes annotées avec `#[Route]`.

Exemple de Contrôleur :

```php
<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\Request;

class ExampleController extends AbstractController
{
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public function base(): array
    {
        return $this->send(['message' => 'Hello World']);
    }

    #[Route(path: '/post', requestType: 'POST', guarded: false)]
    public function post(Request $request): array
    {
        $body = $request->getBody();
        return $this->send(['message' => 'Hello ' . $body['name']]);
    }
}
```

#### Gestion des Middlewares
Les middlewares interceptent les requêtes et les réponses pour ajouter des fonctionnalités telles que l'authentification, la journalisation ou la gestion des erreurs.

##### Implémentation d'un Middleware
Voici un exemple de middleware pour gérer les JWT :

```php
<?php

namespace Sthom\Back\Kernel\Framework\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\JwtManager;

class JwtMiddleware implements MiddlewareInterface
{
    private array $routes;

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->resolveCurrentRoute($request);
        $jwt = JwtManager::getInstance();

        if ($route && $route->isGuarded()) {
            if (!$jwt->isTokenPresent($request) || !$jwt->isValid($request)) {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
                return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            }
        }

        return $handler->handle($request);
    }

    private function resolveCurrentRoute(ServerRequestInterface $request): ?Route
    {
        $path = $request->getUri()->getPath();
        foreach ($this->routes as $route) {
            if ($route->getPath() === $path) {
                return $route;
            }
        }
        return null;
    }
}
```

#### Exécution de l'Application
Pour exécuter l'application, utilisez le serveur PHP intégré ou configurez un serveur web comme Apache ou Nginx.

Exemple d'utilisation du serveur PHP intégré :

```bash
php -S localhost:8000 -t public
```

Assurez-vous que votre point d'entrée est configuré pour rediriger toutes les requêtes vers `public/index.php`.

---

Je vais intégrer les changements récents dans la documentation pour le module routage.

### 6. Module Routage

#### Définition des Routes
Les routes mappent les requêtes HTTP aux méthodes des contrôleurs. Elles sont définies à l'aide de l'annotation `#[Route]` dans vos contrôleurs.

##### Exemple de Route
Voici un exemple de route définie dans un contrôleur :

```php
<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\Request;

class ExampleController extends AbstractController
{
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public function base(): array
    {
        return $this->send(['message' => 'Hello World']);
    }
}
```

#### Annotations de Routes
L'annotation `#[Route]` spécifie le chemin de la route, le type de requête HTTP, et si la route est protégée ou non.

- **path** : Le chemin de la route.
- **requestType** : Le type de requête HTTP (GET, POST, PUT, DELETE, PATCH).
- **guarded** : Un booléen indiquant si la route est protégée par un middleware de sécurité.

#### Exemples de Routes

##### Route GET non protégée
```php
#[Route(path: '/', requestType: 'GET', guarded: false)]
public function base(): array
{
    return $this->send(['message' => 'Hello World']);
}
```

##### Route POST non protégée
```php
#[Route(path: '/post', requestType: 'POST', guarded: false)]
public function post(Request $request): array
{
    $body = $request->getBody();
    return $this->send(['message' => 'Hello ' . $body['name']]);
}
```

##### Route GET protégée
```php
#[Route(path: '/protected', requestType: 'GET', guarded: true)]
public function protected(Request $request): array
{
    return $this->send(['message' => 'This is a protected route']);
}
```

#### Gestion des Routes Dynamiques
Les routes peuvent également contenir des segments dynamiques pour capturer des paramètres d'URL.

##### Exemple de Route Dynamique
```php
#[Route(path: '/user/{id}', requestType: 'GET', guarded: false)]
public function getUser(Request $request): array
{
    $id = $request->getAttribute($id);
    // Fetch user from database using $id
    return $this->send(['id' => $id, 'name' => 'User Name']);
}
```

#### Lecture et Enregistrement des Routes
La lecture des routes est effectuée par la classe `ClassReader`. Cette classe lit les annotations de route dans les contrôleurs et les enregistre pour Slim.

##### Exemple de Lecture des Routes
```php
<?php

namespace Sthom\Back\Kernel\Framework\Utils;

abstract class ClassReader
{
    public final static function readRoutes(): array
    {
        $routes = [];
        $dir = opendir(__DIR__ . '/../../../../Controller');
        while ($filePath = readdir($dir)) {
            if ($filePath !== '.' && $filePath !== '..') {
                $className = str_replace('.php', '', $filePath);
                $filePath = 'Sthom\\Back\\Controller\\' . $className;
                if (class_exists($filePath)) {
                    $controller = new \ReflectionClass($filePath);
                    foreach ($controller->getMethods() as $method) {
                        $attributes = $method->getAttributes();
                        foreach ($attributes as $attribute) {
                            if ($attribute->getName() === 'Sthom\Back\Kernel\Framework\Annotations\Route') {
                                $route = $attribute->newInstance();
                                $route->setController($controller->newInstance());
                                $route->setFn($method->getName());
                                $routes[] = $route;
                            }
                        }
                    }
                }
            }
        }
        closedir($dir);
        return $routes;
    }
}
```

#### Gestion des Routes avec Slim
Une fois les routes lues et enregistrées, elles sont ajoutées à l'application Slim.

##### Exemple d'Ajout de Routes dans le Kernel
```php
<?php

namespace Sthom\Back\Kernel;

use Dotenv\Dotenv;
use Exception;
use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Slim\Factory\AppFactory;
use Sthom\Back\Kernel\Framework\Middlewares\JwtMiddleware;
use Sthom\Back\Kernel\Framework\Model\Model;
use Sthom\Back\Kernel\Framework\Services\Request;
use Sthom\Back\Kernel\Framework\Utils\ClassReader;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;
use Throwable;

class Kernel
{
    use SingletonTrait;

    public final function run(): void
    {
        try {
            // Initialisation de l'environnement
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $env = $dotenv->load();

            // Initialisation du modèle
            $model = Model::getInstance();
            $model->config($env);

            // Initialisation de l'application Slim
            $app = AppFactory::create();
            $isDevMode = $_ENV['APP_ENV'] === 'dev';
            $app->addErrorMiddleware($isDevMode , $isDevMode, $isDevMode);

            // Middleware JWT
            $app->add(new JwtMiddleware());

            // Route OPTIONS pour CORS
            $app->options('/{routes:.+}', function (ServerRequest $serverRequest, ServerResponse $response) {
                return $response;
            });

            // Lecture des routes
            $routes = ClassReader::readRoutes();
            foreach ($routes as $route) {
                $requestType = $route->getRequestType();
                $path = $route->getPath();
                $app->{$requestType}($path, function (ServerRequest $serverRequest, ServerResponse $serverResponse) use ($route) {
                    try {
                        Request::getInstance()->configure($serverRequest);
                        if ($serverRequest->getAttribute('error')) {
                            $serverResponse->getBody()->write(json_encode(['error' => true, 'message' => $serverRequest->getAttribute('error')]));
                        } else {
                            $controller = $route->getController();
                            $response = $controller->{$route->getFn()}(...$route->getParameters());
                            $serverResponse->getBody()->write(json_encode($response));
                        }
                        return $serverResponse
                            ->withHeader('Content-Type', 'application/json')
                            ->withHeader('Access-Control-Allow-Origin', '*')
                            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                            ->withHeader('Access-Control-Max-Age', '86400');
                    } catch (Exception|Throwable $e) {
                        return $this->handleError($serverResponse, $e);
                    }
                });
            }

            $app->run();
        } catch (Exception|Throwable $e) {
            $this->handleFatalError($e);
        }
    }

    private function handleError(ServerResponse $response, Throwable $exception): ServerResponse
    {
        $error = [
            'error' => true,
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ];
        $response->getBody()->write(json_encode($error));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    private function handleFatalError(Throwable $exception): void
    {
        error_log($exception->getMessage());
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => $_ENV['APP_ENV'] === 'dev' ? $exception->getMessage() : 'An error occurred',
        ]);
        exit;
    }
}
```

### Exécution de l'Application
Pour exécuter l'application, utilisez le serveur PHP intégré ou configurez un serveur web comme Apache ou Nginx.

Exemple d'utilisation du serveur PHP intégré :

```bash
php -S localhost:8000 -t public
```

Assurez-vous que votre point d'entrée est configuré pour rediriger toutes les requêtes vers `public/index.php`.

---

### 7. Module Contrôleurs

#### Création et Gestion des Contrôleurs
Les contrôleurs sont responsables de gérer les requêtes HTTP entrantes, de traiter les données et de renvoyer des réponses appropriées. Chaque contrôleur doit étendre la classe `AbstractController`.

#### Exemple de Création d'un Contrôleur
Voici un exemple de création d'un contrôleur avec des routes définies :

```php
<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\Request;
use Sthom\Back\Repository\UserRepository;

class ExampleController extends AbstractController
{
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public function base(): array
    {
        return $this->send(['message' => 'Hello World']);
    }

    #[Route(path: '/post', requestType: 'POST', guarded: false)]
    public function post(Request $request): array
    {
        $body = $request->getBody();
        return $this->send(['message' => 'Hello ' . $body['name']]);
    }

    #[Route(path: '/user/{id}', requestType: 'GET', guarded: false)]
    public function getUser(Request $request, UserRepository $userRepository): array
    {
        $id = $request->getAttribute('id');
        $user = $userRepository->find($id);
        return $this->send(['user' => $user]);
    }
}
```

#### Injection de Dépendances
Les contrôleurs peuvent injecter des services et des repositories pour accéder aux données et aux fonctionnalités partagées.

##### Exemple avec Injection de Dépendances
```php
#[Route(path: '/create', requestType: 'POST', guarded: false)]
public function createUser(Request $request, UserRepository $userRepository, PasswordHasher $hasher): array
{
    $body = $request->getBody();
    $user = new User();
    $user->setEmail($body['email']);
    $user->setMdp($hasher->hash($body['mdp']));
    $user->setRoles('ROLE_USER');
    $user->setNom($body['nom']);
    $user->setPrenom($body['prenom']);
    $userRepository->save($user);
    return $this->send(['message' => "L'utilisateur a été créé"]);
}
```

#### Méthodes des Contrôleurs
Les méthodes des contrôleurs doivent renvoyer des tableaux associatifs qui seront convertis en JSON pour la réponse HTTP.

##### Exemple de Méthode
```php
#[Route(path: '/arg/{name}', requestType: 'GET', guarded: false)]
public function arg(Request $request): array
{
    $name = $request->getAttribute('name');
    return $this->send(['message' => 'Hello ' . $name]);
}
```

#### Envoi de Réponses
Utilisez la méthode `send` pour préparer et renvoyer les données sous forme de tableau. Cette méthode convertira les objets en tableaux associatifs avant de les retourner au client.

```php
public final function send(array $data): array
{
    array_walk_recursive($data, function (&$value) {
        if (is_object($value)) {
            $value = $this->objectToArray($value);
        }
    });
    return $data;
}

private function objectToArray(object $object): array
{
    $reflection = new \ReflectionClass($object);
    $properties = $reflection->getProperties();
    $array = [];
    
    foreach ($properties as $property) {
        $property->setAccessible(true);
        $array[$property->getName()] = $property->getValue($object);
    }
    
    return $array;
}
```

#### Gestion des Erreurs
Utilisez des exceptions pour gérer les erreurs. Le Kernel gérera les exceptions et renverra une réponse JSON appropriée.

##### Exemple de Gestion des Erreurs
```php
public function handleError(ServerResponse $response, Throwable $exception): ServerResponse
{
    $error = [
        'error' => true,
        'message' => $exception->getMessage(),
        'code' => $exception->getCode()
    ];
    $response->getBody()->write(json_encode($error));
    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
}
```

#### Exemple Complet de Contrôleur
```php
<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\Request;
use Sthom\Back\Repository\UserRepository;
use Sthom\Back\Entity\User;
use Sthom\Back\Kernel\Framework\Services\PasswordHasher;

class ExampleController extends AbstractController
{
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public function base(): array
    {
        return $this->send(['message' => 'Hello World']);
    }

    #[Route(path: '/post', requestType: 'POST', guarded: false)]
    public function post(Request $request): array
    {
        $body = $request->getBody();
        return $this->send(['message' => 'Hello ' . $body['name']]);
    }

    #[Route(path: '/user/{id}', requestType: 'GET', guarded: false)]
    public function getUser(Request $request, UserRepository $userRepository): array
    {
        $id = $request->getAttribute('id');
        $user = $userRepository->find($id);
        return $this->send(['user' => $user]);
    }

    #[Route(path: '/create', requestType: 'POST', guarded: false)]
    public function createUser(Request $request, UserRepository $userRepository, PasswordHasher $hasher): array
    {
        $body = $request->getBody();
        $user = new User();
        $user->setEmail($body['email']);
        $user->setMdp($hasher->hash($body['mdp']));
        $user->setRoles('ROLE_USER');
        $user->setNom($body['nom']);
        $user->setPrenom($body['prenom']);
        $userRepository->save($user);
        return $this->send(['message' => "L'utilisateur a été créé"]);
    }
}
```

---

### 8. Module Middleware

#### Introduction aux Middlewares
Les middlewares sont des composants qui interceptent les requêtes HTTP pour ajouter des fonctionnalités comme l'authentification, la journalisation ou la gestion des erreurs. Ils permettent de traiter des requêtes avant qu'elles n'atteignent le contrôleur et de modifier les réponses avant qu'elles ne soient envoyées au client.

#### Implémentation d'un Middleware
Voici comment créer un middleware dans votre projet.

##### Exemple de Middleware JWT
Ce middleware vérifie la présence et la validité d'un token JWT dans les requêtes.

```php
<?php

namespace Sthom\Back\Kernel\Framework\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\JwtManager;

class JwtMiddleware implements MiddlewareInterface
{
    private array $routes;
    private ?Route $route = null;

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->route = $this->resolveCurrentRoute($request);
        $jwt = JwtManager::getInstance();
        if ($this->route && $this->route->isGuarded()) {
            if (!$jwt->isTokenPresent($request)) {
                return $this->unauthorizedResponse($handler, $request, 'Unauthorized access: no JWT token found');
            }
            if (!$jwt->isValid($request)) {
                return $this->unauthorizedResponse($handler, $request, 'Invalid JWT token');
            }
            $user = $this->getUserFromToken($jwt->getPayload($request));
            if (!$user) {
                return $this->unauthorizedResponse($handler, $request, 'User not found');
            }
            $request = $request->withAttribute('user', $user);
        }
        return $handler->handle($request);
    }

    private function unauthorizedResponse(RequestHandlerInterface $handler, ServerRequestInterface $request, string $message): ResponseInterface
    {
        $request = $request->withAttribute('error', $message);
        return $handler->handle($request);
    }

    private function getUserFromToken(array $payload)
    {
        // Fetch user from database using payload
        $identifier = $_ENV['JWT_USER_IDENTIFIER'];
        $table = $_ENV['JWT_USER_TABLE'];
        $model = Model::getInstance();
        return $model->buildQuery()
            ->select($table)
            ->where($identifier, '=', $payload[$identifier])
            ->execute()[0] ?? null;
    }

    private function resolveCurrentRoute(ServerRequestInterface $request): ?Route
    {
        $path = $request->getUri()->getPath();
        foreach ($this->routes as $route) {
            if ($route->getPath() === $path) {
                return $route;
            }
        }
        return null;
    }
}
```

#### Ajout de Middlewares à l'Application Slim
Pour ajouter des middlewares à votre application, utilisez la méthode `add` de Slim.

##### Exemple d'Ajout de Middleware
```php
<?php

namespace Sthom\Back\Kernel;

use Slim\Factory\AppFactory;
use Sthom\Back\Kernel\Framework\Middlewares\JwtMiddleware;
use Sthom\Back\Kernel\Framework\Utils\ClassReader;

class Kernel
{
    use SingletonTrait;

    public final function run(): void
    {
        try {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();

            $app = AppFactory::create();
            $app->addErrorMiddleware(true, true, true);

            $routes = ClassReader::readRoutes();
            $app->add(new JwtMiddleware($routes));

            foreach ($routes as $route) {
                $app->{$route->getRequestType()}($route->getPath(), function ($request, $response) use ($route) {
                    $controller = $route->getController();
                    $result = $controller->{$route->getFn()}();
                    $response->getBody()->write(json_encode($result));
                    return $response->withHeader('Content-Type', 'application/json');
                });
            }

            $app->run();
        } catch (Exception $e) {
            $this->handleFatalError($e);
        }
    }

    private function handleFatalError(Throwable $exception): void
    {
        error_log($exception->getMessage());
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => $_ENV['APP_ENV'] === 'dev' ? $exception->getMessage() : 'An error occurred',
        ]);
        exit;
    }
}
```

#### Exemples de Middlewares

##### Middleware de Journalisation
Un middleware pour journaliser les requêtes entrantes.

```php
<?php

namespace Sthom\Back\Kernel\Framework\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoggingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        error_log($request->getMethod() . ' API FRAMEWORK.md' . $request->getUri()->getPath());
        return $handler->handle($request);
    }
}
```

##### Middleware de Gestion des CORS
Un middleware pour gérer les CORS (Cross-Origin Resource Sharing).

```php
<?php

namespace Sthom\Back\Kernel\Framework\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->withHeader('Access-Control-Max-Age', '86400');
    }
}
```

Ces exemples montrent comment implémenter différents types de middlewares pour étendre les fonctionnalités de votre application tout en maintenant un code clair et modulaire.

---

### 9. Module Modèles et ORM

#### Configuration de la Base de Données
Pour utiliser les fonctionnalités de l'ORM custom de votre framework, vous devez d'abord configurer la connexion à la base de données dans le fichier `.env`.

##### Exemple de Configuration .env
```env
DB_HOST='localhost'
DB_PORT=3306
DB_NAME='api'
DB_USER='admin'
DB_PASS='admin'
```

#### Utilisation de l'ORM Custom
L'ORM custom permet de construire et exécuter des requêtes SQL de manière fluide et sécurisée.

##### Exemple de Configuration du Modèle
Le modèle principal se connecte à la base de données en utilisant les paramètres définis dans le fichier `.env`.

```php
<?php

namespace Sthom\Back\Kernel\Framework\Model;

use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;

class Model
{
    use SingletonTrait;

    private SqlFn $functions;

    public final function config(array $config): void
    {
        if (!array_key_exists("DB_HOST", $config) || !array_key_exists("DB_NAME", $config) || !array_key_exists("DB_PASS", $config) || !array_key_exists("DB_USER", $config)) {
            throw new \Exception('Database configuration error');
        }
        $connection = new \PDO("mysql:host=" . $config["DB_HOST"] . ";dbname=" . $config["DB_NAME"], $config["DB_USER"], $config["DB_PASS"]);
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->functions = new SqlFn($connection);
    }

    public final function buildQuery(): SqlFn
    {
        return $this->functions;
    }
}
```

#### Création et Gestion des Repositories
Les repositories gèrent les interactions avec les entités de la base de données. Ils utilisent les fonctionnalités de l'ORM custom pour effectuer des opérations CRUD.

##### Exemple de Repository
Voici un exemple de repository pour l'entité `User`.

```php
<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;
use Sthom\Back\Kernel\Framework\Model\SqlFn;

class UserRepository extends AbstractRepository
{
    public final function findByRole(string $role): array
    {
        return $this->customQuery()
            ->select("user")
            ->where("roles", "LIKE", "%$role%")
            ->execute();
    }
}
```

#### Exemples de Modèles et Repositories

##### Exemple d'Entité User
```php
<?php

namespace Sthom\Back\Entity;

class User
{
    private ?int $id = null;
    private ?string $email = null;
    private ?string $nom = null;
    private ?string $prenom = null;
    private ?string $roles = null;
    private ?string $mdp = null;

    public final function getId(): ?int
    {
        return $this->id;
    }

    public final function getEmail(): ?string
    {
        return $this->email;
    }

    public final function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public final function getNom(): ?string
    {
        return $this->nom;
    }

    public final function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public final function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public final function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public final function getRoles(): ?string
    {
        return $this->roles;
    }

    public final function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    public final function getMdp(): ?string
    {
        return $this->mdp;
    }

    public final function setMdp(string $mdp): void
    {
        $this->mdp = $mdp;
    }
}
```

##### Exemple de Méthodes dans le Repository
```php
<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;
use Sthom\Back\Entity\User;

class UserRepository extends AbstractRepository
{
    public final function find(int $id): ?User
    {
        $result = $this->customQuery()
            ->select("user")
            ->where("id", "=", $id)
            ->execute();

        if (!empty($result)) {
            return $this->hydrate($result[0], new User());
        }
        return null;
    }

    public final function findAll(): array
    {
        $results = $this->customQuery()
            ->select("user")
            ->execute();

        $users = [];
        foreach ($results as $result) {
            $users[] = $this->hydrate($result, new User());
        }
        return $users;
    }
}
```

#### Méthodes Utiles de l'ORM Custom

##### Sélection
```php
$model = Model::getInstance();
$users = $model->buildQuery()
    ->select("user")
    ->where("roles", "LIKE", "%ROLE_ADMIN%")
    ->execute();
```

##### Insertion
```php
$model = Model::getInstance();
$newUserId = $model->buildQuery()
    ->insert("user", [
        "email" => "newuser@example.com",
        "nom" => "New",
        "prenom" => "User",
        "roles" => "ROLE_USER",
        "mdp" => password_hash("password", PASSWORD_BCRYPT)
    ]);
```

##### Mise à jour
```php
$model = Model::getInstance();
$rowsAffected = $model->buildQuery()
    ->update("user", [
        "email" => "updateduser@example.com"
    ], $userId);
```

##### Suppression
```php
$model = Model::getInstance();
$rowsAffected = $model->buildQuery()
    ->delete("user", $userId);
```

### ORM Custom: Best Practices

- **Validation des Données** : Toujours valider les données avant de les envoyer à la base de données.
- **Requêtes Préparées** : Utiliser des requêtes préparées pour éviter les injections SQL.
- **Transactions** : Utiliser des transactions pour les opérations critiques impliquant plusieurs requêtes.
- **Journalisation** : Consigner les opérations critiques pour le débogage et la traçabilité.

---

### 10. Module Services

#### Définition et Utilisation des Services
Les services sont des composants réutilisables qui encapsulent des fonctionnalités spécifiques comme le hachage des mots de passe, l'envoi d'emails, la gestion des fichiers, etc. Ils sont généralement injectés dans les contrôleurs ou d'autres services pour centraliser les fonctionnalités communes.

#### Exemple de Création d'un Service
Voici un exemple de création d'un service pour le hachage des mots de passe.

##### PasswordHasher Service
```php
<?php

namespace Sthom\Back\Kernel\Framework\Services;

use Sthom\Back\Kernel\Framework\Utils\ServiceInterface;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;

class PasswordHasher implements ServiceInterface
{
    use SingletonTrait;

    /**
     * Cette méthode permet de hasher un mot de passe.
     *
     * @param string $password
     * @param string $algo
     * @return string
     */
    public final function hash(string $password, string $algo = PASSWORD_BCRYPT): string
    {
        return password_hash($password, $algo);
    }

    /**
     * Cette méthode permet de vérifier un mot de passe.
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public final function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
```

#### Injection de Services dans les Contrôleurs
Les services peuvent être injectés dans les contrôleurs via les méthodes des contrôleurs annotées avec `#[Route]`.

##### Exemple d'Injection de Service
```php
<?php

namespace Sthom\Back\Controller;

use Sthom\Back\Kernel\Framework\AbstractController;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Services\Request;
use Sthom\Back\Kernel\Framework\Services\PasswordHasher;
use Sthom\Back\Repository\UserRepository;

class ExampleController extends AbstractController
{
    #[Route(path: '/create', requestType: 'POST', guarded: false)]
    public function createUser(Request $request, UserRepository $userRepository, PasswordHasher $hasher): array
    {
        $body = $request->getBody();
        $user = new User();
        $user->setEmail($body['email']);
        $user->setMdp($hasher->hash($body['mdp']));
        $user->setRoles('ROLE_USER');
        $user->setNom($body['nom']);
        $user->setPrenom($body['prenom']);
        $userRepository->save($user);
        return $this->send(['message' => "L'utilisateur a été créé"]);
    }
}
```

#### Exemples de Services Communs

##### Logger Service
Un service pour enregistrer les logs de l'application.

```php
<?php

namespace Sthom\Back\Kernel\Framework\Services;

use Sthom\Back\Kernel\Framework\Utils\ServiceInterface;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;

class Logger implements ServiceInterface
{
    use SingletonTrait;

    public final function log(string $message, string $level = 'info'): void
    {
        $date = new \DateTime();
        file_put_contents(
            __DIR__ . '/../../../../logs/app.log',
            sprintf("[%s] %s: %s\n", $date->format('Y-m-d H:i:s'), strtoupper($level), $message),
            FILE_APPEND
        );
    }
}
```

##### Mailer Service
Un service pour envoyer des emails.

```php
<?php

namespace Sthom\Back\Kernel\Framework\Services;

use Sthom\Back\Kernel\Framework\Utils\ServiceInterface;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;

class Mailer implements ServiceInterface
{
    use SingletonTrait;

    public final function send(string $to, string $subject, string $body, array $headers = []): bool
    {
        $headers = array_merge($headers, [
            'From' => 'noreply@example.com',
            'Content-Type' => 'text/html; charset=UTF-8'
        ]);

        $formattedHeaders = '';
        foreach ($headers as $key => $value) {
            $formattedHeaders .= "$key: $value\r\n";
        }

        return mail($to, $subject, $body, $formattedHeaders);
    }
}
```

##### Cache Service
Un service pour gérer le cache de l'application.

```php
<?php

namespace Sthom\Back\Kernel\Framework\Services;

use Sthom\Back\Kernel\Framework\Utils\ServiceInterface;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;

class Cache implements ServiceInterface
{
    use SingletonTrait;

    private string $cacheDir;

    public function __construct()
    {
        $this->cacheDir = __DIR__ . '/../../../../cache/';
    }

    public final function get(string $key)
    {
        $filePath = $this->cacheDir . md5($key);
        if (file_exists($filePath)) {
            return unserialize(file_get_contents($filePath));
        }
        return null;
    }

    public final function set(string $key, $value, int $ttl = 3600): void
    {
        $filePath = $this->cacheDir . md5($key);
        file_put_contents($filePath, serialize($value));
        touch($filePath, time() + $ttl);
    }

    public final function delete(string $key): void
    {
        $filePath = $this->cacheDir . md5($key);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
```

#### Création de Services Personnalisés
Vous pouvez créer vos propres services pour encapsuler des fonctionnalités spécifiques à votre application. Suivez les mêmes principes de création et d'injection que les exemples ci-dessus.

---

### 11. Module Sécurité

#### Gestion des JWT (JSON Web Tokens)
Le framework utilise les JWT pour l'authentification et la sécurité des routes. Les JWT sont des tokens signés qui permettent de vérifier l'identité de l'utilisateur.

#### Configuration des JWT
Configurez les chemins des clés privée et publique dans le fichier `.env` :

```env
JWT_PRIVATE_KEY_PATH='config/jwt/private.pem'
JWT_PUBLIC_KEY_PATH='config/jwt/public.pem'
JWT_ISSUER='http://localhost:8000'
```

#### Implémentation du Service JWT
Le service `JwtManager` gère la génération, la validation et le décodage des JWT.

##### Exemple de Service JWT
```php
<?php

namespace Sthom\Back\Kernel\Framework\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;
use Sthom\Back\Kernel\Framework\Utils\ServiceInterface;
use Sthom\Back\Kernel\Framework\Utils\SingletonTrait;

class JwtManager implements ServiceInterface
{
    use SingletonTrait;

    private string $privateKey;
    private string $publicKey;

    private function __construct()
    {
        $this->privateKey = file_get_contents(__DIR__ . '/../../../../' . $_ENV['JWT_PRIVATE_KEY_PATH']);
        $this->publicKey = file_get_contents(__DIR__ . '/../../../../' . $_ENV['JWT_PUBLIC_KEY_PATH']);
    }

    public final function isValid(Request $request): bool
    {
        $authHeader = $request->getHeader('Authorization');
        $token = str_replace('Bearer ', '', $authHeader[0]);
        try {
            JWT::decode($token, new Key($this->publicKey, 'RS256'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public final function getPayload(Request $request): array
    {
        $authHeader = $request->getHeader('Authorization');
        $token = str_replace('Bearer ', '', $authHeader[0]);
        return (array) JWT::decode($token, new Key($this->publicKey, 'RS256'));
    }

    public final function generate(array $data): string
    {
        $data['iat'] = time();
        $data['exp'] = time() + 3600;
        $data['iss'] = $_ENV['JWT_ISSUER'];
        return JWT::encode($data, $this->privateKey, 'RS256');
    }

    public final function decode(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->publicKey, 'RS256'));
    }

    public final function isTokenPresent(Request $request): bool
    {
        $authHeader = $request->getHeader('Authorization');
        return isset($authHeader[0]);
    }
}
```

#### Implémentation des Middlewares de Sécurité
Les middlewares de sécurité vérifient la présence et la validité des JWT dans les requêtes entrantes.

##### Exemple de Middleware JWT
```php
<?php

namespace Sthom\Back\Kernel\Framework\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sthom\Back\Kernel\Framework\Annotations\Route;
use Sthom\Back\Kernel\Framework\Model\Model;
use Sthom\Back\Kernel\Framework\Services\JwtManager;

class JwtMiddleware implements MiddlewareInterface
{
    private array $routes;
    private ?Route $route = null;

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->route = $this->resolveCurrentRoute($request);
        $jwt = JwtManager::getInstance();
        if ($this->route && $this->route->isGuarded()) {
            if (!$jwt->isTokenPresent($request)) {
                $request = $request->withAttribute('error', 'Unauthorized access: no JWT token found');
                return $handler->handle($request);
            }
            if (!$jwt->isValid($request)) {
                $request = $request->withAttribute('error', 'Invalid JWT token');
                return $handler->handle($request);
            }
            $user = $this->getUserFromToken($jwt->getPayload($request));
            if (!$user) {
                $request = $request->withAttribute('error', 'User not found');
            } else {
                $request = $request->withAttribute('user', $user);
            }
        }
        return $handler->handle($request);
    }

    private function resolveCurrentRoute(ServerRequestInterface $request): ?Route
    {
        $path = $request->getUri()->getPath();
        foreach ($this->routes as $route) {
            if ($route->getPath() === $path) {
                return $route;
            }
        }
        return null;
    }

    private function getUserFromToken(array $payload)
    {
        $identifier = $_ENV['JWT_USER_IDENTIFIER'];
        $table = $_ENV['JWT_USER_TABLE'];
        $model = Model::getInstance();
        return $model->buildQuery()
            ->select($table)
            ->where($identifier, '=', $payload[$identifier])
            ->execute()[0] ?? null;
    }
}
```

#### Bonnes Pratiques de Sécurité
Pour garantir la sécurité de votre application, suivez ces meilleures pratiques :

1. **Utilisez HTTPS** : Toujours utiliser HTTPS pour chiffrer les communications entre le client et le serveur.
2. **Stockage Sécurisé des Clés** : Stockez les clés privée et publique dans un endroit sécurisé et non accessible publiquement.
3. **Expiration des Tokens** : Assurez-vous que les tokens JWT ont une durée d'expiration limitée (par exemple, une heure).
4. **Revocation des Tokens** : Implémentez un mécanisme pour révoquer les tokens en cas de compromission.
5. **Validation des Données** : Validez toujours les données côté serveur avant de les traiter.
6. **Journalisation des Accès** : Tenez un journal des accès et des tentatives d'accès pour surveiller les activités suspectes.

---

### 12. Tests et Débogage

#### Introduction aux Tests
Les tests sont essentiels pour garantir la stabilité et la fiabilité de votre application. Ce framework est compatible avec PHPUnit pour l'écriture et l'exécution des tests unitaires et fonctionnels.

#### Installation de PHPUnit
Pour installer PHPUnit, ajoutez-le à votre projet via Composer :

```bash
composer require --dev phpunit/phpunit
```

#### Structure des Tests
Créez un dossier `tests` à la racine de votre projet. Ce dossier contiendra tous vos tests.

```
your-project/
├── tests/
│   ├── Controller/
│   │   └── ExampleControllerTest.php
│   ├── Service/
│   │   └── PasswordHasherTest.php
│   └── bootstrap.php
├── src/
...
```

#### Configuration de PHPUnit
Créez un fichier `phpunit.xml` à la racine de votre projet pour configurer PHPUnit.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="tests/bootstrap.php">
    <testsuites>
        <testsuite name="Application Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

#### Exemple de Test Unitaire
Les tests unitaires permettent de vérifier le bon fonctionnement des unités individuelles de votre code, comme les services et les méthodes des classes.

##### Test du Service PasswordHasher
```php
<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use Sthom\Back\Kernel\Framework\Services\PasswordHasher;

class PasswordHasherTest extends TestCase
{
    public function testHash()
    {
        $hasher = PasswordHasher::getInstance();
        $password = 'secret';
        $hash = $hasher->hash($password);
        $this->assertNotEquals($password, $hash);
        $this->assertTrue($hasher->verify($password, $hash));
    }
}
```

#### Exemple de Test Fonctionnel
Les tests fonctionnels permettent de vérifier le bon fonctionnement des interactions entre plusieurs unités de votre code, comme les contrôleurs et les routes.

##### Test du Contrôleur ExampleController
```php
<?php

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Sthom\Back\Kernel\Framework\Middlewares\JwtMiddleware;
use Sthom\Back\Kernel\Framework\Utils\ClassReader;

class ExampleControllerTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        $this->app = AppFactory::create();
        $routes = ClassReader::readRoutes();
        $this->app->add(new JwtMiddleware($routes));

        foreach ($routes as $route) {
            $this->app->{$route->getRequestType()}($route->getPath(), function ($request, $response) use ($route) {
                $controller = $route->getController();
                $result = $controller->{$route->getFn()}();
                $response->getBody()->write(json_encode($result));
                return $response->withHeader('Content-Type', 'application/json');
            });
        }
    }

    public function testBaseRoute()
    {
        $request = $this->createRequest('GET', '/');
        $response = $this->app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"message":"Hello World"}', (string) $response->getBody());
    }

    private function createRequest(string $method, string $path)
    {
        $uri = (new \Slim\Psr7\Factory\UriFactory())->createUri($path);
        $serverRequest = (new \Slim\Psr7\Factory\ServerRequestFactory())->createServerRequest($method, $uri);
        return $serverRequest;
    }
}
```

#### Débogage
Pour déboguer votre application, vous pouvez utiliser des outils comme le Symfony VarDumper, des logs, et des breakpoints.

##### Utilisation de Symfony VarDumper
Ajoutez Symfony VarDumper à votre projet via Composer :

```bash
composer require --dev symfony/var-dumper
```

Utilisez la fonction `dump` pour afficher les informations de débogage.

```php
use Symfony\Component\VarDumper\VarDumper;

class ExampleController extends AbstractController
{
    public function debugExample(Request $request): array
    {
        $data = $request->getBody();
        VarDumper::dump($data);
        return $this->send(['debug' => $data]);
    }
}
```

##### Journalisation
Utilisez le service Logger pour enregistrer les informations de débogage et les erreurs.

```php
$logger = Logger::getInstance();
$logger->log('This is a debug message', 'debug');
$logger->log('An error occurred', 'error');
```

#### Bonnes Pratiques pour les Tests et le Débogage
1. **Testez Régulièrement** : Exécutez vos tests régulièrement pour détecter les régressions et les bugs.
2. **Couvrez le Code** : Essayez de couvrir un maximum de votre code avec des tests unitaires et fonctionnels.
3. **Utilisez les Logs** : Consignez les erreurs et les informations de débogage pour faciliter le diagnostic des problèmes.
4. **Débogage Interactif** : Utilisez des outils de débogage interactif comme Xdebug pour inspecter l'état de votre application.

---

### Déploiement sur un Serveur Apache

#### Préparation du Serveur
Avant de déployer votre application, assurez-vous que votre serveur Apache est correctement configuré.

##### Exigences du Serveur
- **PHP** : Assurez-vous que la version de PHP utilisée est compatible avec votre application.
- **Extensions PHP** : PDO, cURL, JSON, OpenSSL, mbstring, etc.
- **Apache** : Version 2.4 ou supérieure.
- **Base de Données** : MySQL, PostgreSQL, ou autre SGBD compatible.
- **Composer** : Pour la gestion des dépendances.

##### Installation de PHP et Apache
Sur un serveur Ubuntu, vous pouvez installer Apache et PHP avec les commandes suivantes :

```bash
sudo apt update
sudo apt install apache2
sudo apt install php libapache2-mod-php php-mysql php-curl php-json php-mbstring php-xml php-zip
```

#### Configuration Apache

##### Configuration du Virtual Host
Créez un fichier de configuration pour votre site Apache.

```bash
sudo nano /etc/apache2/sites-available/your-domain.conf
```

Ajoutez le contenu suivant :

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/your-project/public

    <Directory /var/www/your-project/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

##### Activer le Virtual Host et les Modules Apache
Activez le Virtual Host et les modules nécessaires :

```bash
sudo a2ensite your-domain
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Déploiement de l'Application

##### Étape 1: Préparation de l'Environnement
Configurez votre fichier `.env` pour l'environnement de production.

```env
APP_ENV='prod'
DB_HOST='localhost'
DB_PORT=3306
DB_NAME='api'
DB_USER='admin'
DB_PASS='admin'
JWT_USER_TABLE='user'
JWT_USER_IDENTIFIER='email'
JWT_PRIVATE_KEY_PATH='config/jwt/private.pem'
JWT_PUBLIC_KEY_PATH='config/jwt/public.pem'
JWT_ISSUER='https://your-domain.com'
```

##### Étape 2: Transférer les Fichiers
Utilisez `scp` ou un outil de déploiement pour transférer vos fichiers vers le serveur.

```bash
scp -r /path/to/your-project/* your-username@your-server:/var/www/your-project
```

##### Étape 3: Installer les Dépendances
Connectez-vous à votre serveur et installez les dépendances via Composer.

```bash
ssh your-username@your-server
cd /var/www/your-project
composer install --no-dev --optimize-autoloader
```

##### Étape 4: Configurer les Permissions
Assurez-vous que les permissions des fichiers et des répertoires sont correctement configurées.

```bash
sudo chown -R www-data:www-data /var/www/your-project
sudo find /var/www/your-project -type d -exec chmod 755 {} \;
sudo find /var/www/your-project -type f -exec chmod 644 {} \;
```

##### Étape 5: Redémarrer Apache
Redémarrez Apache pour appliquer les modifications.

```bash
sudo systemctl restart apache2
```

#### Configuration du .htaccess
Assurez-vous que le fichier `.htaccess` dans le répertoire `public/` est correctement configuré pour gérer les routes de votre application.

```htaccess
# Désactive l'indexation des répertoires par le serveur web
Options All -Indexes

<IfModule mod_rewrite.c>
  RewriteEngine On

  # Cette condition est utilisée pour obtenir le chemin de base de l'application.
  RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$

  # Cette règle ne fait rien en soi, mais elle définit une variable d'environnement "BASE"
  # qui contient le chemin de base de l'application.
  RewriteRule ^(.*) - [E=BASE:%1]

  # Cette condition vérifie si la requête n'est pas pour un répertoire existant
  RewriteCond %{REQUEST_FILENAME} !-d

  # Cette condition vérifie si la requête n'est pas pour un fichier existant
  RewriteCond %{REQUEST_FILENAME} !-f

  # Si les deux conditions ci-dessus sont vraies, alors la requête est réécrite pour être traitée par index.php.
  RewriteRule ^ index.php [QSA,L]
</IfModule>
```

### Vérification du Déploiement
Accédez à votre domaine (e.g., http://your-domain.com) pour vérifier que votre application est en cours d'exécution correctement. Assurez-vous que toutes les routes et les fonctionnalités fonctionnent comme prévu.

### Automatisation du Déploiement
Pour automatiser le déploiement, vous pouvez utiliser des outils comme GitHub Actions, Jenkins, ou autres. Voici un exemple de configuration de déploiement automatisé avec GitHub Actions :

#### Exemple avec GitHub Actions
Créez un fichier `.github/workflows/deploy.yml` pour configurer GitHub Actions.

```yaml
name: Deploy to Production

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
    - name: Checkout code
      uses: actions/checkout@v2

    - name: Set up PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '7.4'
        extensions: mbstring, intl, curl, json, pdo, mysql

    - name: Install dependencies
      run: composer install --no-dev --optimize-autoloader

    - name: Deploy to server
      uses: easingthemes/ssh-deploy@v2.1.5
      env:
        SSH_PRIVATE_KEY: ${{ secrets.SSH_PRIVATE_KEY }}
        ARGS: "-avz --delete"
        SOURCE: "./"
        REMOTE_HOST: "your-server-ip"
        REMOTE_USER: "your-ssh-username"
        TARGET: "/var/www/your-project"
```

### Bonnes Pratiques de Déploiement
1. **Environnement de Préproduction** : Testez toujours vos déploiements dans un environnement de préproduction avant de déployer en production.
2. **Surveillance** : Utilisez des outils de surveillance pour suivre la performance et la disponibilité de votre application.
3. **Sauvegardes** : Effectuez des sauvegardes régulières de votre base de données et de votre application.
4. **Sécurité** : Assurez-vous que toutes les communications sont sécurisées (HTTPS) et que les permissions des fichiers sont correctement configurées.
5. **Journalisation** : Conservez des logs détaillés pour diagnostiquer les problèmes en production.

---

### 14. FAQ et Ressources

#### FAQ

##### 1. Comment configurer une nouvelle route ?
Pour configurer une nouvelle route, ajoutez une méthode dans votre contrôleur et utilisez l'annotation `#[Route]`.

Exemple :
```php
#[Route(path: '/new-route', requestType: 'GET', guarded: false)]
public function newRoute(): array
{
    return $this->send(['message' => 'This is a new route']);
}
```

##### 2. Comment sécuriser une route avec JWT ?
Ajoutez l'annotation `#[Route]` avec le paramètre `guarded` défini à `true`.

Exemple :
```php
#[Route(path: '/secure-route', requestType: 'GET', guarded: true)]
public function secureRoute(): array
{
    return $this->send(['message' => 'This is a secure route']);
}
```

##### 3. Comment injecter un service dans un contrôleur ?
Ajoutez le service en tant que paramètre de la méthode du contrôleur.

Exemple :
```php
#[Route(path: '/example', requestType: 'POST', guarded: false)]
public function example(Request $request, PasswordHasher $hasher): array
{
    $data = $request->getBody();
    $hashedPassword = $hasher->hash($data['password']);
    return $this->send(['hashedPassword' => $hashedPassword]);
}
```

##### 4. Comment configurer la connexion à la base de données ?
Ajoutez les paramètres de configuration dans le fichier `.env`.

Exemple :
```env
DB_HOST='localhost'
DB_PORT=3306
DB_NAME='api'
DB_USER='admin'
DB_PASS='admin'
```

Puis configurez le modèle dans le Kernel.

```php
$model = Model::getInstance();
$model->config($env);
```

##### 5. Comment déboguer mon application ?
Utilisez des outils comme Symfony VarDumper ou des logs pour afficher les informations de débogage.

Exemple avec la méthode dd() :
```php
use Symfony\Component\VarDumper\VarDumper;

class ExampleController extends AbstractController
{
    public function debugExample(Request $request): array
    {
        $data = $request->getBody();
        dd($data);
        return $this->send(['debug' => $data]);
    }
}
```

#### Ressources Utiles

##### Documentation Officielle
- **Slim Framework** : [Documentation Slim](https://www.slimframework.com/docs/v4/)
- **PHP** : [Documentation PHP](https://www.php.net/docs.php)
- **Composer** : [Documentation Composer](https://getcomposer.org/doc/)

##### Tutoriels et Guides
- **Laravel** : [Laravel From Scratch](https://laracasts.com/series/laravel-8-from-scratch)
- **Symfony** : [SymfonyCasts](https://symfonycasts.com/)

##### Outils de Développement
- **PHPUnit** : [Documentation PHPUnit](https://phpunit.de/documentation.html)
- **Xdebug** : [Documentation Xdebug](https://xdebug.org/docs/)

##### Outils de Déploiement
- **GitHub Actions** : [Documentation GitHub Actions](https://docs.github.com/en/actions)
- **Jenkins** : [Documentation Jenkins](https://www.jenkins.io/doc/)

##### Communautés et Forums
- **Stack Overflow** : [Stack Overflow PHP](https://stackoverflow.com/questions/tagged/php)
- **Reddit** : [r/PHP](https://www.reddit.com/r/php/)
- **PHP Fig** : [PHP-FIG](https://www.php-fig.org/)

---
### 15. Glossaire

#### A

**Annotation**  
Une annotation est un moyen d'ajouter des métadonnées à votre code source. Dans ce framework, les annotations sont utilisées pour définir les routes au sein des contrôleurs.

**API**  
Application Programming Interface. Un ensemble de règles et de définitions qui permet à une application d'interagir avec une autre application.

#### B

**Bootstrap**  
Le processus d'initialisation de l'application, incluant le chargement des configurations et la configuration des services essentiels.

#### C

**Controller**  
Un composant de l'application responsable de gérer les requêtes HTTP et de renvoyer des réponses appropriées. Les contrôleurs contiennent la logique des actions et utilisent les services pour effectuer des opérations.

**Composer**  
Un gestionnaire de dépendances pour PHP. Il permet de déclarer les bibliothèques dont votre projet dépend et de les installer.

#### D

**Dependency Injection**  
Une technique où les objets ou services requis par une classe sont injectés au lieu d'être créés par la classe elle-même, facilitant ainsi le test unitaire et la modularité.

**Dotenv**  
Une bibliothèque qui permet de charger les variables d'environnement à partir d'un fichier `.env`.

#### E

**Entity**  
Un objet qui représente une table dans la base de données. Chaque instance d'une entité correspond à une ligne dans cette table.

**Env (Environment)**  
Les paramètres de configuration de l'application, qui peuvent varier selon l'environnement (développement, test, production). Ils sont généralement stockés dans un fichier `.env`.

#### F

**Framework**  
Un ensemble de bibliothèques et d'outils qui fournissent une structure pour développer des applications, facilitant ainsi le développement et la maintenance.

#### G

**Guarded**  
Une propriété des routes indiquant si l'accès à la route est protégé par un middleware d'authentification (comme le JWT).

#### J

**JWT (JSON Web Token)**  
Un standard ouvert pour la création de tokens d'accès qui permettent de vérifier les identités entre différentes parties.

#### M

**Middleware**  
Un composant logiciel qui intercepte les requêtes HTTP pour ajouter des fonctionnalités, comme l'authentification ou la gestion des erreurs, avant que la requête n'atteigne le contrôleur.

**Model**  
Une classe qui représente et gère les données de l'application. Le modèle inclut des méthodes pour interagir avec la base de données.

#### O

**ORM (Object-Relational Mapping)**  
Une technique de programmation pour convertir les données entre des systèmes de types incompatibles en utilisant la programmation orientée objet.

#### P

**PDO (PHP Data Objects)**  
Une extension PHP qui définit une interface pour accéder aux bases de données.

**PSR (PHP Standards Recommendation)**  
Un ensemble de standards et de recommandations pour le développement en PHP, publiés par le PHP-FIG.

#### R

**Repository**  
Un composant de l'application qui encapsule la logique nécessaire pour accéder aux sources de données et les manipuler.

**Request**  
Un objet représentant une requête HTTP. Il contient des informations sur la méthode, les en-têtes, les paramètres, etc.

**Route**  
Une définition qui mappe une URL à une méthode de contrôleur. Les routes déterminent comment les requêtes HTTP sont traitées par l'application.

#### S

**Service**  
Un composant réutilisable qui encapsule une fonctionnalité spécifique, comme le hachage des mots de passe ou l'envoi d'emails.

**Singleton**  
Un modèle de conception qui restreint l'instanciation d'une classe à une seule instance.

**Slim**  
Un micro-framework PHP utilisé pour développer des applications web et des APIs.

#### T

**Trait**  
Un mécanisme de réutilisation du code dans les langages de programmation orientés objet. Un trait est une collection de méthodes que vous pouvez inclure dans plusieurs classes.

**Token**  
Un jeton est une séquence de caractères utilisée pour authentifier et vérifier les utilisateurs dans une application.

#### U

**Unit Test**  
Un test qui vérifie le comportement d'une petite partie du code, généralement une seule méthode ou une classe.

#### V

**Validation**  
Le processus de vérification des données entrantes pour s'assurer qu'elles sont correctes et sécurisées avant de les traiter.

**Virtual Host**  
Une méthode utilisée par les serveurs web pour héberger plusieurs domaines (avec leurs propres documents distincts) sur un seul serveur.

---