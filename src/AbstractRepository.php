<?php

namespace Sthom\Back;
use JetBrains\PhpStorm\NoReturn;
use Opis\Database\Database;
use Opis\Database\SQL\Query;
use Sthom\Back\Database\AbstractEntity;
use Sthom\Back\Database\EntityManager;

/**
 * Cette classe fournit des fonctionnalités de base pour les repositories de l'application
 * Une classe repository est une classe qui permet de gérer les entités de l'application
 * Elle se base sur la classe {@link EntityManager} pour gérer les entités et utilise le namespace d'une entité pour mapper une table en objet
 * - Elle permet de persister, de supprimer, de récupérer et de rechercher des entités
 * - Elle permet aussi de construire des requêtes SQL
 *
 * Fournit des fonctionnalités de base pour les repositories de l'application.
 */
abstract class AbstractRepository
{
    /**
     * Constructeur de la classe AbstractRepository
     * Il prend en paramètre le nom de l'entité et le nom de la table
     *
     * @param string $entityName
     * @param string $table
     */
    public final function __construct(private readonly string $entityName, private readonly string $table)
    {
    }

    /**
     * Cette méthode permet de sauvegarder une entité dans la base de données (insérer ou mettre à jour)
     *
     * @param AbstractEntity $entity
     * @return void
     */
    public final function save(AbstractEntity $entity): void
    {
        EntityManager::getInstance()->persist($entity, $this->table);
    }

    /**
     * Cette méthode permet de supprimer une entité de la base de données
     *
     * @param AbstractEntity $entity
     * @return void
     */
    #[NoReturn]
    public final function remove(AbstractEntity $entity): void
    {
        EntityManager::getInstance()->remove($entity, $this->table);
    }

    /**
     * Cette méthode permet de récupérer une entité à partir de son identifiant
     *
     * @param int $id
     * @return AbstractEntity
     */
    public final function find(int $id): AbstractEntity
    {
        return EntityManager::getInstance()->find($id, $this->table, $this->entityName);
    }

    /**
     * Cette méthode permet de récupérer toutes les entités de la table
     *
     * @return array
     */
    public final function findAll(): array
    {
        return EntityManager::getInstance()->findAll($this->table, $this->entityName);
    }

    /**
     * Cette méthode permet de récupérer les entités qui correspondent à un ensemble de conditions
     *
     * @param array $conditions
     * @return array
     */
    public final function findBy(array $conditions): array
    {
        return EntityManager::getInstance()->findBy($this->table, $conditions, $this->entityName);
    }

    /**
     * Cette méthode permet de récupérer une entité qui correspond à un ensemble de conditions
     *
     * @param array $conditions
     * @return AbstractEntity
     */
    public final function findOneBy(array $conditions): AbstractEntity
    {
        return EntityManager::getInstance()->findOneBy($this->table, $conditions,$this->entityName);
    }

    /**
     * Cette méthode permet de construire une requête SQL
     *
     * @return Query
     */
    public final function queryBuilder(): Query
    {
        return Container::getInstance()->get(Database::class)->query();
    }

}
