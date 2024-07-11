<?php

namespace Sthom\Back;
use Opis\ORM\Core\EntityQuery;
use Opis\ORM\Entity;
use Opis\ORM\EntityManager;

/**
 * Classe abstraite AbstractRepository
 *
 * Fournit des fonctionnalités de base pour les repositories de l'application.
 */
abstract class AbstractRepository
{
    private EntityManager $entityManager;

    public function __construct(private readonly string $entityClass) {
        $this->entityManager = Container::getInstance()->get(EntityManager::class);
    }

    public final function queryBuilder(): EntityQuery {
        return $this->entityManager->query($this->entityClass);
    }

    public final function find(int $id): Entity {
        return $this->entityManager->query($this->entityClass)->find($id);
    }

    public final function findAll(): array {

        return $this->entityManager->query($this->entityClass)->all();
    }

    public final function save(Entity $entity): void {
        $this->entityManager->save($entity);
    }

    public final function delete(Entity $entity): void {
        $this->entityManager->delete($entity);
    }



}
