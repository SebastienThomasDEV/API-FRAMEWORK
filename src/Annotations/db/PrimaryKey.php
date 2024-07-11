<?php

namespace Sthom\Back\Annotations\db;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PrimaryKey extends AbstractColumn
{
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