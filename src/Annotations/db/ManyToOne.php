<?php

namespace Sthom\Back\Annotations\db;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ManyToOne extends AbstractColumnRelation
{

    public function __construct(
        public string $targetEntity,
        public string $inversedBy,
    ) {
    }

    public final function getTargetEntity(): string
    {
        return $this->targetEntity;
    }

    public final function getInversedBy(): string
    {
        return $this->inversedBy;
    }

}