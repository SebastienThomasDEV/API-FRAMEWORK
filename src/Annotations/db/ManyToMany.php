<?php

namespace Sthom\Back\Annotations\db;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ManyToMany
{

    public function __construct(
        public string $targetEntity,
        public string $groupedBy,

    ) {
    }

}