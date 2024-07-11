<?php

namespace Sthom\Back\Parser;
use ReflectionClass;
use Sthom\Back\Annotations\db\AbstractColumn;
use Sthom\Back\Annotations\db\PrimaryKey;
use Sthom\Back\Annotations\Table;
use Sthom\Back\Annotations\UserTable;
use Sthom\Back\Container;
use Sthom\Back\Utils\UserInterface;

class EntityReader extends AbstractReader
{

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
            if ($entity->implementsInterface(UserInterface::class) && $annotation = $entity->getAttributes( UserTable::class)[0]) {
                $annotation = $annotation->newInstance();
                $user = new \stdClass();
                $user->entity = $entity->getName();
                $user->table = $annotation->getName();
                $user->identifier = $annotation->getIdentifier();
                Container::getInstance()->set("user", $user);
            }
            $table = self::getEntityTable($entity);

            $properties = self::getEntityProperties($entity);
            $entities[$table->getName()] = [
                'entity' => $entity->getName(),
                'table' => $table,
                'properties' => $properties,
            ];
        }
        return $entities;
    }

    private static function getEntityProperties(ReflectionClass $entity): array
    {
        $properties = [];
        foreach ($entity->getProperties() as $property) {
            $annotations = $property->getAttributes();
            $annotation = array_filter($annotations, fn($attr) => is_subclass_of($attr->getName(), AbstractColumn::class))[0];
            $column = $annotation->newInstance();
            $properties[$property->getName()] = $column;
        }
        return $properties;
    }


    private static function getEntityTable(ReflectionClass $entity): Table
    {
        $table = $entity->getAttributes(Table::class)[0] ?? $entity->getAttributes(UserTable::class)[0];
        return $table->newInstance();
    }







}