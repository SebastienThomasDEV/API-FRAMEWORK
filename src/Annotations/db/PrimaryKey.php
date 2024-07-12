<?php

namespace Sthom\Back\Annotations\db;

/**
 * Cette annotation permet de définir une colonne comme clé primaire
 * Elle etend la classe abstraite {@link AbstractColumn}
 *
 * @var string $name
 * @var string $type
 * @var int $length
 * @var bool $nullable
 *
 * @package Sthom\Back\Annotations\db
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PrimaryKey extends AbstractColumn
{
    /**
     * Constructeur de la classe PrimaryKey
     *
     * @param string $name
     * @param string $type
     * @param int $length
     * @param bool $nullable
     */
    public function __construct(
        string $name,
        string $type,
        int    $length = 255,
        bool   $nullable = false,
    )
    {
        parent::__construct($name, $type, $length, $nullable);
    }
}