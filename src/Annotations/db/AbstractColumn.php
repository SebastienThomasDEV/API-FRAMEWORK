<?php

namespace Sthom\Back\Annotations\db;

/**
 * Cette classe abstraite représente une colonne de base de données
 * Elle est utilisée pour définir les colonnes des entités de la base de données en tant qu'annotations
 * Elle sera étendue par les classes concrètes qui définiront les colonnes :
 *  - {@see Column}
 *  - {@see PrimaryKey}
 *
 * @var string $name
 * @var string $type
 * @var int $length
 * @var bool $nullable
 *
 * @package Sthom\Back\Annotations\db
 */
abstract class AbstractColumn
{

    /**
     * Constructeur de la classe AbstractColumn
     *
     * @param string $name
     * @param string $type
     * @param int $length
     * @param bool $nullable
     */
    public function __construct(
        private readonly string $name,
        private readonly string $type,
        public readonly int     $length = 255,
        public readonly bool    $nullable = false,
    ) {}

    // Getters
    public final function getName(): string
    {
        return $this->name;
    }

    public final function getType(): string
    {
        return $this->type;
    }

    public final function getLength(): int
    {
        return $this->length;
    }

    public final function isNullable(): bool
    {
        return $this->nullable;
    }



}