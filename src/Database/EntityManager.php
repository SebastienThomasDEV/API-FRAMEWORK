<?php

namespace Sthom\Back\Database;

use JetBrains\PhpStorm\NoReturn;
use Opis\Database\Database;
use Sthom\Back\Container;
use Sthom\Back\Utils\Logger;
use Sthom\Back\Utils\SingletonTrait;

/**
 * Classe EntityManager
 *
 * Cette classe permet de gérer les entités de l'application
 * Cette classe est un singleton
 *
 * @package Sthom\Back\Database
 *
 * @see SingletonTrait
 */
class EntityManager
{
    use SingletonTrait;

    /**
     * Cette méthode permet de persister une entité dans la base de données
     *
     * @param AbstractEntity $entity
     * @param string $table
     *
     * @return void
     */
    #[NoReturn]
    public final function persist(AbstractEntity $entity, string $table): void
    {
        if ($entity->getId() === null) {
            try {
                $this->insert($entity, $table);
            } catch (\Exception $e) {
                Logger::error($e->getMessage());
            }
        } else {
            try {
                $this->update($entity, $table);
            } catch (\Exception $e) {
                Logger::error($e->getMessage());
            }
        }
    }

    /**
     * Cette méthode permet d'insérer une entité dans la base de données
     *
     * @param AbstractEntity $entity
     * @param string $tableName
     *
     * @return void
     * @throws \Exception
     */
    #[NoReturn]
    private function insert(AbstractEntity $entity, string $tableName): void
    {
        if ($entity->isFullyHydrated()) {
            Container::getInstance()->get(Database::class)->insert($entity->toArray())->into($tableName);
        } else {
            throw new \Exception("Entity is not fully hydrated cannot persist");
        }
    }

    /**
     *
     * Cette méthode permet de mettre à jour une entité dans la base de données
     *
     * @param AbstractEntity $entity
     * @param string $tableName
     *
     * @return void
     * @throws \Exception
     */
    #[NoReturn]
    private function update(AbstractEntity $entity, string $tableName): void
    {
        if ($entity->isInitialized()) {
            Container::getInstance()->get(Database::class)->update($tableName)->where('id')->is($entity->getId())->set($entity->toArray());
        } else {
            throw new \Exception("Entity is not initialized in the database");
        }
    }


    /**
     * Cette méthode permet de supprimer une entité de la base de données
     *
     * @param AbstractEntity $entity
     * @param string $table
     *
     * @return void
     */
    #[NoReturn]
    public final function remove(AbstractEntity $entity, string $table): void
    {
        if ($entity->getId() !== null) {
            try {
                Container::getInstance()->get(Database::class)->delete()->from($table)->where('id')->is($entity->getId());
            } catch (\Exception $e) {
                Logger::error($e->getMessage());
            }
        }
    }


    /**
     * Cette méthode permet de trouver une entité par son identifiant
     *
     * @param int $id
     * @param string $table
     * @param string $class
     *
     * @return AbstractEntity|null
     */
    public final function find(int $id, string $table, string $class): ?AbstractEntity
    {
        try {
            $result = Container::getInstance()->get(Database::class)->from($table)->where('id')->is($id)->select()->all();
            if (count($result) === 1) {
                return $class::create(json_decode(json_encode($result[0]), true));
            }
            return null;
        } catch (\Exception $e) {
            Logger::error($e->getMessage());
        }
        return null;
    }

    /**
     * Cette méthode permet de trouver toutes les entités d'une table
     *
     * @param string $table
     * @param string $class
     *
     * @return array
     */
    public final function findAll(string $table, string $class): array
    {
        try {
            $entities = [];
            foreach (Container::getInstance()->get(Database::class)->from($table)->select()->all() as $entity) {
                $entities[] = $class::create(json_decode(json_encode($entity), true));
            }
            return $entities;
        } catch (\Exception $e) {
            Logger::error($e->getMessage());
        }
        return [];
    }

    /**
     * Cette méthode permet de trouver des entités par des conditions
     *
     * @param string $table
     * @param array $conditions
     * @param string $class
     *
     * @return array
     */
    public final function findBy(string $table, array $conditions, string $class): array
    {
        try {
            $entities = [];
            foreach (Container::getInstance()->get(Database::class)->from($table)->where($conditions)->select()->all() as $entity) {
                $entities[] = $class::create(json_decode(json_encode($entity), true));
            }
            return $entities;
        } catch (\Exception $e) {
            Logger::error($e->getMessage());
        }
        return [];
    }

    /**
     * Cette méthode permet de trouver une entité par des conditions
     *
     * @param string $table
     * @param array $conditions
     * @param string $class
     *
     * @return AbstractEntity|null
     */
    public final function findOneBy(string $table, array $conditions, string $class): ?AbstractEntity
    {
        try {
            $result = Container::getInstance()->get(Database::class)->from($table)->where($conditions)->select()->all();
            if (count($result) === 1) {
                return $class::create(json_decode(json_encode($result[0]), true));
            }
            return null;
        } catch (\Exception $e) {
            Logger::error($e->getMessage());
        }
        return null;
    }
}