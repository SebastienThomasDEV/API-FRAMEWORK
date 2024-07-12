<?php

namespace Sthom\Back\Annotations\db;


/**
 * Cette annotation permet de définir une colonne dans une table
 * Elle etend la classe abstraite {@link AbstractColumn}
 *
 * @var string $name
 * @var string $type
 * @var int $length
 * @var bool $nullable
 * @var bool $unique
 *
 * @package Sthom\Back\Annotations\db
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Column extends AbstractColumn
{
    /**
     * Constructeur de la classe Column
     *
     * @param string $name
     * @param string $type
     * @param int $length
     * @param bool $nullable
     * @param bool $unique
     */
    public function __construct(
        string               $name,
        string               $type,
        int                  $length = 255,
        bool                 $nullable = false,
        public readonly bool $unique = false,
    )
    {
        parent::__construct($name, $type, $length, $nullable);
    }

    // Getters
    public final function isUnique(): bool
    {
        return $this->unique;
    }


}