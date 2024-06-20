<?php

namespace Sthom\Back\Kernel\Framework\Annotations\Mapper;

use Sthom\Back\Kernel\Framework\Annotations\Mapper\Relations\Relation;

/**
 * Classe Entity pour définir les entités de l'application.
 * Cette classe est utilisée comme une annotation pour définir les entités dans les contrôleurs.
 *
 *
 * @package Sthom\Back\Kernel\Framework\Annotations
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Entity
{


    private string $table;

    private string $name;

    private array $fields = [];


    public function __construct(string $table)
    {
        $this->table = $table;

    }


    public final function getTable(): string
    {
        return $this->table;
    }

    public final function addField(Column | Relation $field): void
    {
        $this->fields[] = $field;
    }

    public final function getFields(): array
    {
        return $this->fields;
    }

    public final function getName(): string
    {
        return $this->name;
    }

    public final function setName(string $name): void
    {
        $this->name = $name;
    }





}