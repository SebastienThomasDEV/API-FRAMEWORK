<?php

namespace Sthom\Back\Parser;

use ReflectionClass;
use Sthom\Back\Annotations\db\AbstractColumn;
use Sthom\Back\Annotations\db\AbstractColumnRelation;
use Sthom\Back\Annotations\Entity;
use Sthom\Back\Annotations\UserEntity;
use Sthom\Back\Container;
use Sthom\Back\Database\UserInterface;

/**
 * Cette classe permet de lire les entités de l'application (les classes qui sont annotées par l'annotation {@link Entity})
 * Elle étend la classe {@link AbstractReader}
 * Elle lira les fichiers du dossier "Entity" et retournera les entités sous forme de tableau
 * @package Sthom\Back\Parser
 */
class EntityReader extends AbstractReader
{
    /**
     * Cette méthode permet de récupérer les entités de l'application sous forme de tableau
     * Elle retourne un tableau associatif dont les clés sont les noms des tables et les valeurs sont des tableaux contenant les informations des entités
     *
     * @return array
     */
    public final function getEntities(): array
    {
        $files = $this->getFiles();
        $entities = [];
        foreach ($files as $filePath) {
            $className = str_replace('.php', '', $filePath);
            $fullClassName = self::getNamespace() . '\\' . $className;
            if (!class_exists($fullClassName)) {
                continue;
            }
            $entity = new ReflectionClass($fullClassName);
            if ($entity->implementsInterface(UserInterface::class) && $annotation = $entity->getAttributes(UserEntity::class)[0]) {
                $annotation = $annotation->newInstance();
                $user = new \stdClass();
                $user->entity = $entity->getName();
                $user->table = $annotation->getName();
                $user->identifier = $annotation->getIdentifier();
                $user->repository = $annotation->getRepository();
                Container::getInstance()->set("user", $user);
            }
            $table = self::getEntityTable($entity);
            $properties = self::getEntityProperties($entity);
            $relations = self::getEntityRelations($entity);
            $entities[$table->getName()] = [
                'entity' => $entity->getName(),
                'table' => $table,
                'properties' => $properties,
                'repository' => $table->getRepository(),
                'relations' => $relations
            ];
        }
        return $entities;
    }

    /**
     * Cette méthode permet de récupérer les propriétés d'une entité sous forme de tableau
     *
     * @param ReflectionClass $entity
     * @return array
     */
    private static function getEntityProperties(ReflectionClass $entity): array
    {
        $properties = [];
        foreach ($entity->getProperties() as $property) {
            $annotations = $property->getAttributes();
            $annotation = array_filter($annotations, fn($attr) => is_subclass_of($attr->getName(), AbstractColumn::class));
            if (count($annotation) === 0) {
                continue;
            }
            $annotation = $annotation[array_key_first($annotation)];
            if ($annotation === null) {
                continue;
            }
            $column = $annotation->newInstance();
            $properties[$property->getName()] = $column;
        }
        return $properties;
    }

    private static function getEntityRelations(ReflectionClass $entity): array
    {
        $relations = [];
        foreach ($entity->getProperties() as $property) {
            $annotations = $property->getAttributes();
            $annotation = array_filter($annotations, fn($attr) => is_subclass_of($attr->getName(), AbstractColumnRelation::class));
            if (count($annotation) === 0) {
                continue;
            }
            $annotation = $annotation[array_key_first($annotation)];
            if ($annotation === null) {
                continue;
            }
            $column = $annotation->newInstance();
            $relations[$property->getName()] = $column;
        }
        return $relations;
    }

    /**
     * Cette méthode permet de récupérer les informations de la table d'une entité
     *
     * @param ReflectionClass $entity
     * @return Entity
     */
    private static function getEntityTable(ReflectionClass $entity): Entity
    {
        $table = $entity->getAttributes(Entity::class)[0] ?? $entity->getAttributes(UserEntity::class)[0];
        return $table->newInstance();
    }


}