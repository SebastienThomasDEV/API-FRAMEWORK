Compris. Voici un README ajusté pour expliquer les fonctionnalités disponibles dans votre script CLI sans entrer dans les détails d'un projet complet.

### README

# Générateur CLI pour PHP

Ce script propose une interface en ligne de commande (CLI) permettant de générer automatiquement des contrôleurs et des entités pour un projet PHP.

## Utilisation

Exécutez le script principal pour démarrer la CLI :

```bash
php cli/console.php
```

### Commandes disponibles

#### 1. Création d'un contrôleur

Lorsque vous choisissez l'option `controller`, vous serez invité à entrer le nom du contrôleur à créer.

- Sélectionnez `controller`.
- Entrez le nom du contrôleur (ex. `UserController`).
- Le fichier du contrôleur sera généré dans le dossier `src/Controller`.

Le contrôleur généré aura la structure suivante :

```php
<?php

namespace Sthom\Back\Controller;

class {controller} extends AbstractController
{
    #[Route(path: '/', requestType: 'GET', guarded: false)]
    public final function base(): array
    {
        return $this->send([
            'message' => 'Hello World',
        ]);
    }
}
```

#### 2. Création d'une entité

Lorsque vous choisissez l'option `entity`, vous serez invité à entrer le nom de l'entité ainsi que ses propriétés.

- Sélectionnez `entity`.
- Entrez le nom de l'entité (ex. `User`).
- Entrez les propriétés de l'entité jusqu'à ce que vous saisissiez `exit`.

Exemple de session de création d'entité :

```
Veuillez entrer le nom de l'entité à créer: User
Veuillez entrer une propriété de votre entité User (exit pour terminer): id
Veuillez entrer le type de la propriété: int
Veuillez entrer une propriété de votre entité User (exit pour terminer): name
Veuillez entrer le type de la propriété: string
Veuillez entrer une propriété de votre entité User (exit pour terminer): exit
```

L'entité générée aura la structure suivante :

```php
<?php

namespace Sthom\Back\Entity;

class {entity}
{
    private ?int $id = null;
    private ?string $name = null;

    public final function getId(): ?int
    {
        return $this->id;
    }

    public final function setId(int $id): void
    {
        $this->id = $id;
    }

    public final function getName(): ?string
    {
        return $this->name;
    }

    public final function setName(string $name): void
    {
        $this->name = $name;
    }
}
```

#### 3. Création d'un dépôt

Lorsque vous créez une entité, un dépôt correspondant est également généré automatiquement.

Le dépôt généré aura la structure suivante :

```php
<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class {repository} extends AbstractRepository
{
    // Repository methods here
}
```

## Structure du script

- `main.php` : Script principal pour exécuter la CLI.
- `Cli.php` : Classe `Cli` contenant les méthodes pour interagir avec l'utilisateur.
- `EntityCreator.php` : Classe pour générer les entités.
- `ControllerCreator.php` : Classe pour générer les contrôleurs.
- `templates.php` : Fichier contenant les templates pour les entités, contrôleurs, et dépôts.
