<?php

namespace Sthom\Back\Annotations\db;


#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Column extends AbstractColumn
{

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

    public final function isUnique(): bool
    {
        return $this->unique;
    }


}