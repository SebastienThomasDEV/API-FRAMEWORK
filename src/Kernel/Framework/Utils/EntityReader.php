<?php

namespace Sthom\Back\Kernel\Framework\Utils;

use Sthom\Back\Kernel\Framework\Annotations\Mapper\Column;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Entity;
use Sthom\Back\Kernel\Framework\Annotations\Mapper\Relations\Relation;

abstract class EntityReader
{
    /**
     * Lit les annotations de la classe et retourne les champs de l'entité.
     *
     * @param string $class Nom complet de la classe à analyser.
     * @return object L'entité avec ses champs.
     */
    public static function read(string $class): object
    {
        $reflectionClass = new \ReflectionClass($class);
        $entityAnnotation = $reflectionClass->getAttributes();
        if (empty($entityAnnotation)) {
            throw new \RuntimeException("No entity annotation found in class $class.");
        } else if ($entityAnnotation[0]->getName() !== Entity::class) {
            throw new \RuntimeException("Invalid entity annotation found in class $class.");
        } else {
            $entityAnnotation = $entityAnnotation[0]->newInstance();
        }
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $annotations = $property->getAttributes();
            foreach ($annotations as $annotation) {
                if ($annotation->getName() === Column::class || $annotation->newInstance() instanceof Relation) {
                    $field = $annotation->newInstance();
                    $entityAnnotation->addField($field);
                    $entityAnnotation->setName($class);
                } else {
                    throw new \RuntimeException("Invalid field annotation found in class $class.");
                }
            }
        }
        return $entityAnnotation;
    }

    /**
     * Lit les annotations de toutes les classes du namespace Entity et retourne les entités.
     *
     * @return array Les entités avec leurs champs.
     */
    public static function getEntities(string $namespace = 'Sthom\Back\App\Entity', string $dir = __DIR__ . '/../../../App/Entity/'): array
    {
        $entities = [];
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $class = $namespace . '\\' . pathinfo($file, PATHINFO_FILENAME);
                $entities[] = self::read($class);
            }
        }
        return $entities;
    }







}