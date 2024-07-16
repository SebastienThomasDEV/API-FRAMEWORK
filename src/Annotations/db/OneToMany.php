<?php

namespace Sthom\Back\Annotations\db;


#[\Attribute(\Attribute::TARGET_PROPERTY)]
class OneToMany extends AbstractColumnRelation
{
    public function __construct(
        public string $targetEntity,
        public string $mappedBy,
    ) {
    }

    public final function getTargetEntity(): string
    {
        return $this->targetEntity;
    }

    public final function getMappedBy(): string
    {
        return $this->mappedBy;
    }

}